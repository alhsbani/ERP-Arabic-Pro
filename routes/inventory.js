const express = require('express');
const router = express.Router();
const Inventory = require('../models/Inventory');
const Product = require('../models/Product');
const Warehouse = require('../models/Warehouse');

// Get all inventory items
router.get('/', async (req, res) => {
  try {
    const inventory = await Inventory.find()
      .populate('product')
      .populate('warehouse');
    res.json({
      success: true,
      count: inventory.length,
      data: inventory
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Get inventory by product
router.get('/product/:productId', async (req, res) => {
  try {
    const inventory = await Inventory.find({ product: req.params.productId })
      .populate('product')
      .populate('warehouse');
    res.json({
      success: true,
      count: inventory.length,
      data: inventory
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Get inventory by warehouse
router.get('/warehouse/:warehouseId', async (req, res) => {
  try {
    const inventory = await Inventory.find({ warehouse: req.params.warehouseId })
      .populate('product')
      .populate('warehouse');
    res.json({
      success: true,
      count: inventory.length,
      data: inventory
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Add inventory
router.post('/', async (req, res) => {
  try {
    const { product, warehouse, quantity } = req.body;

    // Check if product and warehouse exist
    const productExists = await Product.findById(product);
    const warehouseExists = await Warehouse.findById(warehouse);

    if (!productExists || !warehouseExists) {
      return res.status(400).json({ success: false, message: 'المنتج أو المخزن غير موجود' });
    }

    let inventory = await Inventory.findOne({ product, warehouse });

    if (inventory) {
      inventory.quantity += quantity;
      await inventory.save();
    } else {
      inventory = new Inventory(req.body);
      await inventory.save();
    }

    res.status(201).json({ success: true, message: 'تم إضافة المخزون بنجاح', data: inventory });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Update inventory quantity
router.put('/:id', async (req, res) => {
  try {
    const inventory = await Inventory.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    ).populate('product').populate('warehouse');

    if (!inventory) {
      return res.status(404).json({ success: false, message: 'المخزون غير موجود' });
    }

    res.json({ success: true, message: 'تم تحديث المخزون بنجاح', data: inventory });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Delete inventory
router.delete('/:id', async (req, res) => {
  try {
    const inventory = await Inventory.findByIdAndDelete(req.params.id);
    if (!inventory) {
      return res.status(404).json({ success: false, message: 'المخزون غير موجود' });
    }
    res.json({ success: true, message: 'تم حذف المخزون بنجاح' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

module.exports = router;