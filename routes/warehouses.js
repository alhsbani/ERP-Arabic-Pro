const express = require('express');
const router = express.Router();
const Warehouse = require('../models/Warehouse');
const { body, validationResult } = require('express-validator');

// Get all warehouses
router.get('/', async (req, res) => {
  try {
    const warehouses = await Warehouse.find();
    res.json({
      success: true,
      count: warehouses.length,
      data: warehouses
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Get warehouse by ID
router.get('/:id', async (req, res) => {
  try {
    const warehouse = await Warehouse.findById(req.params.id);
    if (!warehouse) {
      return res.status(404).json({ success: false, message: 'المخزن غير موجود' });
    }
    res.json({ success: true, data: warehouse });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Create new warehouse
router.post('/', [
  body('name').notEmpty().withMessage('اسم المخزن مطلوب'),
  body('location').notEmpty().withMessage('الموقع مطلوب')
], async (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ success: false, errors: errors.array() });
  }

  try {
    const warehouse = new Warehouse(req.body);
    await warehouse.save();
    res.status(201).json({ success: true, message: 'تم إضافة المخزن بنجاح', data: warehouse });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Update warehouse
router.put('/:id', async (req, res) => {
  try {
    const warehouse = await Warehouse.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    );
    if (!warehouse) {
      return res.status(404).json({ success: false, message: 'المخزن غير موجود' });
    }
    res.json({ success: true, message: 'تم تحديث المخزن بنجاح', data: warehouse });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Delete warehouse
router.delete('/:id', async (req, res) => {
  try {
    const warehouse = await Warehouse.findByIdAndDelete(req.params.id);
    if (!warehouse) {
      return res.status(404).json({ success: false, message: 'المخزن غير موجود' });
    }
    res.json({ success: true, message: 'تم حذف المخزن بنجاح' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

module.exports = router;