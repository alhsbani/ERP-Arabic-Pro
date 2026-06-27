const mongoose = require('mongoose');

const warehouseSchema = new mongoose.Schema({
  name: {
    type: String,
    required: [true, 'يرجى إدخال اسم المخزن'],
    trim: true
  },
  location: String,
  manager: String,
  phone: String,
  capacity: {
    type: Number,
    default: 0
  },
  currentStock: {
    type: Number,
    default: 0
  },
  status: {
    type: String,
    enum: ['نشط', 'معطل'],
    default: 'نشط'
  },
  notes: String,
  createdAt: {
    type: Date,
    default: Date.now
  },
  updatedAt: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model('Warehouse', warehouseSchema);