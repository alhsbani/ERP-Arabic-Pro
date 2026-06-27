const express = require('express');
const router = express.Router();
const Product = require('../models/Product');
const JsBarcode = require('jsbarcode');
const Canvas = require('canvas');
const { body, validationResult } = require('express-validator');

// Get all products
router.get('/', async (req, res) => {
  try {
    const products = await Product.find();
    res.json({
      success: true,
      count: products.length,
      data: products
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Get product by ID
router.get('/:id', async (req, res) => {
  try {
    const product = await Product.findById(req.params.id);
    if (!product) {
      return res.status(404).json({ success: false, message: 'المنتج غير موجود' });
    }
    res.json({ success: true, data: product });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Create new product
router.post('/', [
  body('name').notEmpty().withMessage('اسم المنتج مطلوب'),
  body('sku').notEmpty().withMessage('كود المنتج مطلوب'),
  body('category').notEmpty().withMessage('الفئة مطلوبة'),
  body('costPrice').isNumeric().withMessage('سعر التكلفة يجب أن يكون رقم'),
  body('sellingPrice').isNumeric().withMessage('سعر البيع يجب أن يكون رقم')
], async (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ success: false, errors: errors.array() });
  }

  try {
    const product = new Product(req.body);
    // Generate barcode if not provided
    if (!product.barcode) {
      product.barcode = product.sku + Date.now();
    }
    await product.save();
    res.status(201).json({ success: true, message: 'تم إضافة المنتج بنجاح', data: product });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Update product
router.put('/:id', async (req, res) => {
  try {
    const product = await Product.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    );
    if (!product) {
      return res.status(404).json({ success: false, message: 'المنتج غير موجود' });
    }
    res.json({ success: true, message: 'تم تحديث المنتج بنجاح', data: product });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Delete product
router.delete('/:id', async (req, res) => {
  try {
    const product = await Product.findByIdAndDelete(req.params.id);
    if (!product) {
      return res.status(404).json({ success: false, message: 'المنتج غير موجود' });
    }
    res.json({ success: true, message: 'تم حذف المنتج بنجاح' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

module.exports = router;