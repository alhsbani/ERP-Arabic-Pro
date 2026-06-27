const express = require('express');
const router = express.Router();
const Sale = require('../models/Sale');
const Customer = require('../models/Customer');
const Product = require('../models/Product');
const Inventory = require('../models/Inventory');
const moment = require('moment');

// Sales Report
router.get('/sales', async (req, res) => {
  try {
    const { startDate, endDate } = req.query;
    const query = {};

    if (startDate || endDate) {
      query.saleDate = {};
      if (startDate) query.saleDate.$gte = new Date(startDate);
      if (endDate) query.saleDate.$lte = new Date(endDate);
    }

    const sales = await Sale.find(query).populate('customer');
    const totalSales = sales.reduce((sum, sale) => sum + sale.total, 0);
    const totalProfit = sales.reduce((sum, sale) => {
      const profit = sale.subtotal - (sale.subtotal * 0.8); // Assuming 80% cost
      return sum + profit;
    }, 0);

    res.json({
      success: true,
      data: {
        totalSales,
        totalProfit,
        salesCount: sales.length,
        averageSale: totalSales / sales.length || 0,
        sales
      }
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Customer Report
router.get('/customers', async (req, res) => {
  try {
    const customers = await Customer.find();
    const totalCustomers = customers.length;
    const totalBalance = customers.reduce((sum, c) => sum + c.balance, 0);
    const activeCusto = customers.filter(c => c.status === 'نشط').length;

    res.json({
      success: true,
      data: {
        totalCustomers,
        activeCustomers: activeCusto,
        totalBalance,
        customers
      }
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Inventory Report
router.get('/inventory', async (req, res) => {
  try {
    const inventory = await Inventory.find()
      .populate('product')
      .populate('warehouse');

    const lowStockItems = inventory.filter(item => 
      item.quantity < item.product.minStock
    );

    const totalInventoryValue = inventory.reduce((sum, item) => 
      sum + (item.quantity * item.product.costPrice), 0
    );

    res.json({
      success: true,
      data: {
        totalItems: inventory.length,
        lowStockItems,
        lowStockCount: lowStockItems.length,
        totalInventoryValue,
        inventory
      }
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Product Report
router.get('/products', async (req, res) => {
  try {
    const products = await Product.find();
    const sales = await Sale.find();

    const productSales = {};
    sales.forEach(sale => {
      sale.items.forEach(item => {
        const productId = item.product.toString();
        productSales[productId] = (productSales[productId] || 0) + item.quantity;
      });
    });

    const topProducts = products
      .map(p => ({
        ...p.toObject(),
        sold: productSales[p._id.toString()] || 0
      }))
      .sort((a, b) => b.sold - a.sold)
      .slice(0, 10);

    res.json({
      success: true,
      data: {
        totalProducts: products.length,
        topProducts
      }
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Dashboard Summary
router.get('/dashboard', async (req, res) => {
  try {
    const today = moment().startOf('day').toDate();
    const thisMonth = moment().startOf('month').toDate();

    const todaySales = await Sale.find({ saleDate: { $gte: today } });
    const monthSales = await Sale.find({ saleDate: { $gte: thisMonth } });
    const customers = await Customer.find();
    const products = await Product.find();
    const inventory = await Inventory.find();

    const todayTotal = todaySales.reduce((sum, s) => sum + s.total, 0);
    const monthTotal = monthSales.reduce((sum, s) => sum + s.total, 0);

    res.json({
      success: true,
      data: {
        todaySales: todayTotal,
        monthlySales: monthTotal,
        totalSalesCount: todaySales.length,
        totalCustomers: customers.length,
        activeCustomers: customers.filter(c => c.status === 'نشط').length,
        totalProducts: products.length,
        totalInventoryItems: inventory.length
      }
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

module.exports = router;