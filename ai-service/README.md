# Product Q&A AI Service

AI-powered answer generation service using Transformers.js with WebGPU acceleration.

## 🚀 Features

- ⚡ **WebGPU Acceleration**: Lightning-fast inference using GPU
- 🤖 **Qwen 2.5-3B Model**: State-of-the-art instruction-following model
- 🔒 **100% Local**: No third-party APIs, all processing on your server
- 🎯 **Context-Aware**: Uses full product information for accurate answers
- 📦 **Lightweight**: Node.js + Transformers.js (no Python needed)

## 📋 Requirements

- **Node.js**: v20.0.0 or higher
- **Browser/Runtime**: Chrome/Edge 113+ with WebGPU support OR Node.js 20+ with experimental WebGPU
- **RAM**: 8GB minimum (model loaded in memory)
- **Storage**: 10GB free space (for model cache)
- **GPU**: WebGPU-compatible GPU recommended (optional but faster)

## 🔧 Installation

1. **Navigate to the ai-service directory:**
   ```bash
   cd ai-service
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Start the service:**
   ```bash
   npm start
   ```

   Or for development with auto-reload:
   ```bash
   npm run dev
   ```

4. **Wait for model to load:**
   ```
   🤖 Loading Qwen 2.5-3B-Instruct model...
   ⚡ Using WebGPU for acceleration
   ✅ Model loaded successfully!
   🚀 AI Service running on http://localhost:3000
   ```

## 🌐 API Endpoints

### Health Check
```bash
GET http://localhost:3000/health
```

**Response:**
```json
{
  "status": "ready",
  "model": "Qwen/Qwen2.5-3B-Instruct",
  "device": "webgpu",
  "version": "2.0.0"
}
```

### Generate Answer
```bash
POST http://localhost:3000/generate-answer
Content-Type: application/json

{
  "question": "What is this product made of?",
  "product_name": "Cotton T-Shirt",
  "product_description": "Comfortable cotton t-shirt with crew neck",
  "product_short_description": "100% cotton, machine washable",
  "product_price": 29.99,
  "product_sku": "TSHIRT-001",
  "product_type": "simple",
  "product_color": "Blue",
  "product_size": "M",
  "product_material": "Cotton"
}
```

**Response:**
```json
{
  "answer": "This product is made of 100% cotton. It's a comfortable, breathable fabric that's machine washable for easy care.",
  "model": "qwen-2.5-3b-instruct",
  "processing_time": 1234,
  "device": "webgpu"
}
```

## ⚙️ Configuration

### Environment Variables

Create a `.env` file:

```env
PORT=3000
NODE_ENV=production
```

### Model Settings (in server.js)

```javascript
const result = await textGenerator(prompt, {
    max_new_tokens: 200,      // Max response length
    temperature: 0.7,          // Creativity (0.1-1.0)
    top_p: 0.9,                // Nucleus sampling
    repetition_penalty: 1.2    // Avoid repetition
});
```

## 🐛 Troubleshooting

### Model not loading?

1. **Check Node.js version:**
   ```bash
   node --version  # Should be 20.0.0 or higher
   ```

2. **Check disk space:**
   ```bash
   df -h  # Need at least 10GB free
   ```

3. **Check WebGPU support:**
   - Chrome/Edge: Visit `chrome://gpu` → Check "WebGPU" status
   - Node.js: Ensure using version 20+ with experimental WebGPU

### Slow inference?

- **Use WebGPU**: Ensure your GPU is WebGPU-compatible
- **Reduce max_new_tokens**: Lower to 100-150 for faster responses
- **Use CPU fallback**: Change `device: 'webgpu'` to `device: 'cpu'` (slower but works everywhere)

### Out of memory?

- **Reduce model size**: Consider using a smaller model
- **Increase system RAM**: 8GB minimum, 16GB recommended
- **Close other applications**: Free up memory

## 📊 Performance

**Typical Response Times:**
- With WebGPU: 1-3 seconds
- With CPU only: 10-30 seconds

**Resource Usage:**
- RAM: ~6GB (model loaded)
- GPU VRAM: ~3-4GB
- CPU: Minimal when using GPU

## 🔐 Security

- ✅ **No external APIs**: All processing happens locally
- ✅ **No data leaves server**: Complete privacy
- ✅ **CORS enabled**: Configure allowed origins in production
- ⚠️ **Rate limiting**: Implement in production (use express-rate-limit)

## 🚀 Production Deployment

### Using PM2 (Process Manager)

```bash
# Install PM2
npm install -g pm2

# Start service
pm2 start server.js --name productqna-ai

# Enable auto-restart on server reboot
pm2 startup
pm2 save

# Monitor
pm2 monit
```

### Using Docker

```dockerfile
FROM node:20-bullseye

WORKDIR /app
COPY package*.json ./
RUN npm ci --production
COPY . .

EXPOSE 3000
CMD ["node", "server.js"]
```

```bash
docker build -t productqna-ai .
docker run -p 3000:3000 productqna-ai
```

## 📝 Testing

### Manual Test

```bash
curl -X POST http://localhost:3000/generate-answer \
  -H "Content-Type: application/json" \
  -d '{
    "question": "What size should I order?",
    "product_name": "Running Shoes",
    "product_description": "Lightweight running shoes with breathable mesh",
    "product_size": "Available in sizes 7-12"
  }'
```

### Load Testing

Use Apache Bench or similar:
```bash
ab -n 100 -c 10 -p test-payload.json -T application/json http://localhost:3000/generate-answer
```

## 🔄 Updating the Model

To use a different model:

1. Change model name in `server.js`:
   ```javascript
   textGenerator = await pipeline(
       'text-generation',
       'your-model-name-here',  // Change this
       { device: 'webgpu', dtype: 'fp16' }
   );
   ```

2. Restart the service

## 📚 Resources

- [Transformers.js Docs](https://huggingface.co/docs/transformers.js)
- [Qwen 2.5 Model Card](https://huggingface.co/Qwen/Qwen2.5-3B-Instruct)
- [WebGPU Specification](https://www.w3.org/TR/webgpu/)

## 🤝 Support

For issues or questions:
- Email: giyasmahmudmozahed@gmail.com
- Check Magento logs: `var/log/system.log`
- Check service logs: `pm2 logs productqna-ai`

## 📄 License

MIT License - See LICENSE file
