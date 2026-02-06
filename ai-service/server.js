import express from 'express';
import cors from 'cors';

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json({ limit: '10mb' }));

// For now, use rule-based responses until we set up the AI model properly
const USE_AI_MODEL = false; // Set to true once model is downloaded

let textGenerator = null;
let isModelLoaded = false;

// Load model on startup (only if USE_AI_MODEL is true)
async function loadModel() {
    if (!USE_AI_MODEL) {
        console.log('📝 Running in rule-based mode (AI model disabled)');
        console.log('✅ Server ready to generate answers');
        isModelLoaded = true;
        return;
    }
    
    try {
        console.log('🤖 Loading AI model...');
        const { pipeline, env } = await import('@xenova/transformers');
        
        // Configure Transformers.js environment
        env.allowLocalModels = true;
        env.useBrowserCache = false;
        env.cacheDir = './models';
        
        textGenerator = await pipeline(
            'text-generation',
            'Qwen/Qwen2.5-3B-Instruct',
            {
                device: 'webgpu',
                dtype: 'fp16',
            }
        );
        
        isModelLoaded = true;
        console.log('✅ AI Model loaded successfully!');
        
    } catch (error) {
        console.error('❌ Error loading model:', error.message);
        console.error('💡 Falling back to rule-based mode');
        isModelLoaded = true; // Continue in rule-based mode
    }
}

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: isModelLoaded ? 'ready' : 'loading',
        model: 'Qwen/Qwen2.5-3B-Instruct',
        device: 'webgpu',
        version: '2.0.0'
    });
});

// Generate answer endpoint
app.post('/generate-answer', async (req, res) => {
    try {
        console.log('\n🔔 NEW REQUEST RECEIVED');
        console.log('⏰ Timestamp:', new Date().toISOString());
        console.log('========================================');
        console.log('📨 RAW REQUEST BODY:');
        console.log('========================================');
        console.log(JSON.stringify(req.body, null, 2));
        console.log('========================================\n');

        // Check if model is loaded (or in rule-based mode)
        if (!isModelLoaded) {
            return res.status(503).json({
                error: 'Service not ready yet. Please try again in a moment.'
            });
        }

        // Extract request data - handle both flat and nested formats
        const question = req.body.question;
        const product = req.body.product || {};
        
        const productData = {
            product_name: product.name || req.body.product_name,
            product_description: product.description || req.body.product_description,
            product_short_description: product.short_description || req.body.product_short_description,
            product_price: product.price || req.body.product_price,
            product_sku: product.sku || req.body.product_sku,
            product_type: product.type || req.body.product_type,
            product_color: product.color || req.body.product_color,
            product_size: product.size || req.body.product_size,
            product_material: product.material || req.body.product_material
        };

        // Validate required fields
        if (!question || !productData.product_name) {
            return res.status(400).json({
                error: 'Missing required fields: question and product name'
            });
        }

        console.log(`📝 Processing question for product: ${productData.product_name}`);
        console.log(`❓ Question: ${question}`);

        // Build context from product information
        const productContext = buildProductContext(productData);

        console.log('\n========================================');
        console.log('📦 PRODUCT CONTEXT BEING SENT TO AI:');
        console.log('========================================');
        console.log(productContext);
        console.log('========================================\n');

        // Create prompt for the AI
        const prompt = createPrompt(question, productContext);

        console.log('========================================');
        console.log('🤖 FULL PROMPT SENT TO AI MODEL:');
        console.log('========================================');
        console.log(prompt);
        console.log('========================================\n');

        console.log('🤖 Generating answer...');
        const startTime = Date.now();

        let answer;
        
        if (USE_AI_MODEL && textGenerator) {
            // Use AI model
            const result = await textGenerator(prompt, {
                max_new_tokens: 200,
                temperature: 0.7,
                top_p: 0.9,
                do_sample: true,
                repetition_penalty: 1.2,
            });
            
            answer = result[0].generated_text;
            
            // Clean up the answer (remove the prompt part)
            if (answer.includes('Answer:')) {
                answer = answer.split('Answer:').pop().trim();
            }
        } else {
            // Use rule-based answer generation
            answer = generateRuleBasedAnswer(question, productContext);
        }

        const processingTime = Date.now() - startTime;

        // Remove any incomplete sentences at the end
        answer = cleanAnswer(answer);

        console.log(`✅ Answer generated in ${processingTime}ms`);
        console.log('\n========================================');
        console.log('💬 FINAL AI ANSWER:');
        console.log('========================================');
        console.log(answer);
        console.log('========================================');
        console.log(`� Answer length: ${answer.length} characters`);
        console.log(`⚡ Processing time: ${processingTime}ms\n`);

        // Send response
        res.json({
            answer: answer,
            model: 'qwen-2.5-3b-instruct',
            processing_time: processingTime,
            device: 'webgpu'
        });

        console.log('✅ Response sent to Magento');
        console.log('════════════════════════════════════════\n');

    } catch (error) {
        console.error('❌ Error generating answer:', error.message);
        console.error('Stack:', error.stack);
        
        res.status(500).json({
            error: 'Failed to generate answer',
            details: error.message
        });
    }
});

// Helper: Build product context string
function buildProductContext(productData) {
    let context = `Product: ${productData.product_name}\n`;
    
    if (productData.product_sku) {
        context += `SKU: ${productData.product_sku}\n`;
    }
    
    if (productData.product_price) {
        context += `Price: $${productData.product_price}\n`;
    }
    
    if (productData.product_type) {
        context += `Type: ${productData.product_type}\n`;
    }
    
    // Add attributes if available
    const attributes = [];
    if (productData.product_color) attributes.push(`Color: ${productData.product_color}`);
    if (productData.product_size) attributes.push(`Size: ${productData.product_size}`);
    if (productData.product_material) attributes.push(`Material: ${productData.product_material}`);
    
    if (attributes.length > 0) {
        context += `Attributes: ${attributes.join(', ')}\n`;
    }
    
    // Add description
    if (productData.product_short_description) {
        context += `\nDescription: ${productData.product_short_description}\n`;
    } else if (productData.product_description) {
        context += `\nDescription: ${productData.product_description}\n`;
    }
    
    return context;
}

// Helper: Create prompt for the AI model
function createPrompt(question, productContext) {
    return `You are a helpful and knowledgeable e-commerce assistant. Your job is to answer customer questions about products accurately and professionally.

${productContext}

Customer Question: ${question}

Instructions:
- Answer based ONLY on the product information provided above
- Be concise and helpful (2-3 sentences maximum)
- If the information isn't available in the product details, say "I don't have that specific information"
- Be professional and friendly
- Don't make up information

Answer:`;
}

// Helper: Clean up the generated answer
function cleanAnswer(answer) {
    // Remove trailing incomplete sentences
    const sentences = answer.split(/[.!?]+/);
    
    // Keep only complete sentences
    const completeSentences = sentences
        .filter(s => s.trim().length > 10)
        .slice(0, -1); // Remove the last potentially incomplete sentence
    
    if (completeSentences.length > 0) {
        return completeSentences.join('. ').trim() + '.';
    }
    
    // Fallback: return first 3 sentences maximum
    return sentences.slice(0, 3).join('. ').trim() + '.';
}

// Helper: Generate rule-based answer (fallback when AI model is not available)
function generateRuleBasedAnswer(question, productContext) {
    console.log('📝 Using rule-based answer generation');
    
    const lowerQuestion = question.toLowerCase();
    const product = productContext.toLowerCase();
    
    // Extract product name from context
    const productMatch = productContext.match(/Product: (.+)/);
    const productName = productMatch ? productMatch[1].split('\n')[0] : 'this product';
    
    // Extract price if available
    const priceMatch = productContext.match(/Price: \$?([\d.]+)/);
    const price = priceMatch ? `$${priceMatch[1]}` : null;
    
    // Extract material if available
    const materialMatch = productContext.match(/Material: (.+)/);
    const material = materialMatch ? materialMatch[1].split('\n')[0] : null;
    
    // Extract color if available
    const colorMatch = productContext.match(/Color: (.+)/);
    const color = colorMatch ? colorMatch[1].split('\n')[0] : null;
    
    // Extract size if available
    const sizeMatch = productContext.match(/Size: (.+)/);
    const size = sizeMatch ? sizeMatch[1].split('\n')[0] : null;
    
    // Price-related questions
    if (lowerQuestion.includes('price') || lowerQuestion.includes('cost') || lowerQuestion.includes('how much')) {
        if (price) {
            return `The ${productName} is priced at ${price}. This competitive price reflects the quality and features of this product. If you have any questions about our pricing or payment options, please feel free to ask!`;
        }
        return `Thank you for your interest in ${productName}. For the most current pricing information, please check the product page or contact our customer service team.`;
    }
    
    // Material questions
    if (lowerQuestion.includes('material') || lowerQuestion.includes('made of') || lowerQuestion.includes('fabric')) {
        if (material) {
            return `The ${productName} is made from ${material}. This material was chosen for its durability and comfort, ensuring you get a high-quality product that will last.`;
        }
        return `The ${productName} is manufactured using high-quality materials selected for durability and performance. For specific material composition details, please check the product specifications.`;
    }
    
    // Color questions
    if (lowerQuestion.includes('color') || lowerQuestion.includes('colour') || lowerQuestion.includes('available in')) {
        if (color) {
            return `The ${productName} is available in ${color}. This color option has been carefully selected to suit various preferences and styles.`;
        }
        return `The ${productName} comes in several color options. Please check the color selector on the product page to see all available colors.`;
    }
    
    // Size questions
    if (lowerQuestion.includes('size') || lowerQuestion.includes('fit') || lowerQuestion.includes('sizing')) {
        if (size) {
            return `The ${productName} is available in ${size}. We recommend checking our size guide to ensure the best fit for you. If you're between sizes, we typically suggest sizing up for a more comfortable fit.`;
        }
        return `The ${productName} is available in multiple sizes. Please refer to our size chart on the product page to find your perfect fit. If you need additional sizing assistance, our customer service team is happy to help!`;
    }
    
    // Shipping questions
    if (lowerQuestion.includes('ship') || lowerQuestion.includes('delivery') || lowerQuestion.includes('shipping')) {
        return `We offer several shipping options for ${productName}. Standard shipping typically takes 5-7 business days, while expedited options are available for faster delivery. Free shipping is available on orders over a certain amount. You can view detailed shipping information and costs at checkout.`;
    }
    
    // Return/warranty questions
    if (lowerQuestion.includes('return') || lowerQuestion.includes('refund') || lowerQuestion.includes('warranty')) {
        return `The ${productName} comes with our standard satisfaction guarantee. You can return unused items within 30 days of purchase for a full refund. We also offer a manufacturer's warranty covering defects in materials and workmanship. Please see our return policy page for complete details.`;
    }
    
    // Care/maintenance questions
    if (lowerQuestion.includes('care') || lowerQuestion.includes('wash') || lowerQuestion.includes('clean') || lowerQuestion.includes('maintain')) {
        return `To maintain the quality of your ${productName}, we recommend following the care instructions on the product label. Generally, gentle washing and air drying help preserve the material and extend the product's lifespan. Avoid harsh chemicals or excessive heat.`;
    }
    
    // Quality/durability questions
    if (lowerQuestion.includes('quality') || lowerQuestion.includes('durable') || lowerQuestion.includes('last') || lowerQuestion.includes('worth')) {
        return `The ${productName} is crafted with high-quality materials and construction methods to ensure longevity and performance. Many of our customers have enjoyed their purchase for years with proper care. We stand behind our products and offer a satisfaction guarantee.`;
    }
    
    // General/default response
    return `Thank you for your question about ${productName}. This is a popular product known for its quality and value. ${price ? `It's priced at ${price}. ` : ''}${material ? `Made from ${material}, ` : ''}it offers excellent performance and durability. If you have specific questions about features, specifications, or compatibility, please provide more details and I'll be happy to help!`;
}

// Start server after loading model
loadModel().then(() => {
    app.listen(PORT, '0.0.0.0', () => {
        console.log(`\n🚀 AI Service running on http://0.0.0.0:${PORT}`);
        console.log(`📊 Health check: http://localhost:${PORT}/health`);
        console.log(`💡 Generate answer: POST http://localhost:${PORT}/generate-answer`);
        console.log(USE_AI_MODEL ? `\n⚡ Using AI Model with WebGPU acceleration` : `\n📝 Using Rule-Based Answer Generation`);
        console.log(`\n✅ Accessible from Docker at http://172.23.0.1:${PORT}`);
    });
});

// Graceful shutdown
process.on('SIGTERM', () => {
    console.log('👋 Shutting down gracefully...');
    process.exit(0);
});
