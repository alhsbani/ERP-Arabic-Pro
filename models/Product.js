const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
  name: {
    type: String,
    required: [true, 'يرجى إدخال اسم المنتج'],
    trim: true
  },
  sku: {
    type: String,
    required: [true, 'كود المنتج مطلوب'],
    unique: true,
    uppercase: true
  },
  barcode: {
    type: String,
    unique: true,
    sparse: true
  },
  description: String,
  category: {
    type: String,
    required: [true, 'يرجى اختيار الفئة']
  },
  unit: {
    type: String,
    required: true,
    enum: ['عبوة', 'كيس', 'صندوق', 'قطعة', 'لتر', 'كيلو']
  },
  costPrice: {
    type: Number,
    required: [true, 'سعر التكلفة مطلوب']
  },
  sellingPrice: {
    type: Number,
    required: [true, 'سعر البيع مطلوب']
  },
  minStock: {
    type: Number,
    default: 0
  },
  image: String,
  status: {
    type: String,
    enum: ['متاح', 'غير متاح', 'متوقف'],
    default: 'متاح'
  },
  createdAt: {
    type: Date,
    default: Date.now
  },
  updatedAt: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model('Product', productSchema);