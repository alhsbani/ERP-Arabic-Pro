const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const dotenv = require('dotenv');
const path = require('path');

dotenv.config();

const app = express();

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ limit: '50mb', extended: true }));
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Database Connection
mongoose.connect(process.env.MONGODB_URI || 'mongodb://localhost:27017/erp-arabic-pro', {
  useNewUrlParser: true,
  useUnifiedTopology: true,
})
.then(() => console.log('✅ اتصال قاعدة البيانات نجح'))
.catch(err => console.log('❌ خطأ في الاتصال:', err));

// Routes
app.use('/api/auth', require('./routes/auth'));
app.use('/api/customers', require('./routes/customers'));
app.use('/api/products', require('./routes/products'));
app.use('/api/inventory', require('./routes/inventory'));
app.use('/api/sales', require('./routes/sales'));
app.use('/api/reports', require('./routes/reports'));
app.use('/api/barcode', require('./routes/barcode'));

// Health Check
app.get('/api/health', (req, res) => {
  res.json({ 
    status: '✅ سارية', 
    message: 'نظام إدارة المبيعات والعملاء والمخازن يعمل بنجاح',
    timestamp: new Date()
  });
});

// Error Handling Middleware
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({
    success: false,
    message: 'حدث خطأ في السيرفر',
    error: process.env.NODE_ENV === 'production' ? {} : err.message
  });
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`🚀 السيرفر يعمل على المنفذ ${PORT}`);
  console.log(`📊 نظام إدارة المبيعات والعملاء والمخازن - جاهز للعمل`);
});

module.exports = app;
