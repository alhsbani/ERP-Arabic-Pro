const mongoose = require('mongoose');

const saleSchema = new mongoose.Schema({
  invoiceNumber: {
    type: String,
    required: true,
    unique: true
  },
  customer: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Customer',
    required: true
  },
  items: [
    {
      product: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Product',
        required: true
      },
      quantity: {
        type: Number,
        required: true
      },
      unitPrice: Number,
      total: Number
    }
  ],
  subtotal: {
    type: Number,
    required: true
  },
  tax: {
    type: Number,
    default: 0
  },
  discount: {
    type: Number,
    default: 0
  },
  total: {
    type: Number,
    required: true
  },
  paidAmount: {
    type: Number,
    default: 0
  },
  remainingAmount: Number,
  paymentMethod: {
    type: String,
    enum: ['نقد', 'شيك', 'تحويل بنكي', 'آجل'],
    default: 'نقد'
  },
  paymentStatus: {
    type: String,
    enum: ['مدفوع', 'معلق', 'جزئي'],
    default: 'مدفوع'
  },
  status: {
    type: String,
    enum: ['مكتمل', 'معلق', 'ملغي'],
    default: 'مكتمل'
  },
  notes: String,
  saleDate: {
    type: Date,
    default: Date.now
  },
  dueDate: Date,
  createdAt: {
    type: Date,
    default: Date.now
  },
  updatedAt: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model('Sale', saleSchema);