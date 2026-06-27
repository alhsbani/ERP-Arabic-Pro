const express = require('express');
const router = express.Router();
const Sale = require('../models/Sale');
const Product = require('../models/Product');
const Customer = require('../models/Customer');
const Inventory = require('../models/Inventory');

// Get all sales
router.get('/', async (req, res) => {
  try {
    const sales = await Sale.find()
      .populate('customer')
      .populate('items.product')
      .sort({ saleDate: -1 });
    res.json({
      success: true,
      count: sales.length,
      data: sales
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Get sale by ID
router.get('/:id', async (req, res) => {
  try {
    const sale = await Sale.findById(req.params.id)
      .populate('customer')
      .populate('items.product');
    if (!sale) {
      return res.status(404).json({ success: false, message: 'الفاتورة غير موجودة' });
    }
    res.json({ success: true, data: sale });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Create new sale
router.post('/', async (req, res) => {
  try {
    const { customer, items, tax, discount, paymentMethod } = req.body;

    // Validate customer
    const customerExists = await Customer.findById(customer);
    if (!customerExists) {
      return res.status(400).json({ success: false, message: 'العميل غير موجود' });
    }

    // Calculate totals
    let subtotal = 0;
    for (const item of items) {
      const product = await Product.findById(item.product);
      if (!product) {
        return res.status(400).json({ success: false, message: `المنتج ${item.product} غير موجود` });
      }
      const itemTotal = product.sellingPrice * item.quantity;
      subtotal += itemTotal;
      item.unitPrice = product.sellingPrice;
      item.total = itemTotal;
    }

    const taxAmount = (subtotal * (tax || 0)) / 100;
    const discountAmount = discount || 0;
    const total = subtotal + taxAmount - discountAmount;

    // Generate invoice number
    const lastSale = await Sale.findOne().sort({ _id: -1 });
    const invoiceNumber = lastSale ? 
      'INV-' + (parseInt(lastSale.invoiceNumber.split('-')[1]) + 1) : 
      'INV-1001';

    const sale = new Sale({
      invoiceNumber,
      customer,
      items,
      subtotal,
      tax: taxAmount,
      discount: discountAmount,
      total,
      paidAmount: total,
      remainingAmount: 0,
      paymentMethod: paymentMethod || 'نقد',
      paymentStatus: 'مدفوع'
    });

    await sale.save();

    // Update customer balance
    customerExists.totalPurchases += total;
    await customerExists.save();

    res.status(201).json({ success: true, message: 'تم إضافة الفاتورة بنجاح', data: sale });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Update sale
router.put('/:id', async (req, res) => {
  try {
    const sale = await Sale.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    ).populate('customer').populate('items.product');

    if (!sale) {
      return res.status(404).json({ success: false, message: 'الفاتورة غير موجودة' });
    }
    res.json({ success: true, message: 'تم تحديث الفاتورة بنجاح', data: sale });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Delete sale
router.delete('/:id', async (req, res) => {
  try {
    const sale = await Sale.findByIdAndDelete(req.params.id);
    if (!sale) {
      return res.status(404).json({ success: false, message: 'الفاتورة غير موجودة' });
    }
    res.json({ success: true, message: 'تم حذف الفاتورة بنجاح' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

module.exports = router;