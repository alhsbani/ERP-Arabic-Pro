const express = require('express');
const router = express.Router();
const Product = require('../models/Product');
const JsBarcode = require('jsbarcode');
const SVGtoPDF = require('svg-to-pdfkit');
const PDFDocument = require('pdfkit');
const fs = require('fs');
const path = require('path');

// Generate barcode image
router.post('/generate', async (req, res) => {
  try {
    const { barcode, format = 'CODE128' } = req.body;

    if (!barcode) {
      return res.status(400).json({ success: false, message: 'الباركود مطلوب' });
    }

    // Generate SVG barcode
    const svgString = JsBarcode(barcode, {
      format: format,
      width: 2,
      height: 50,
      displayValue: true
    }).render();

    res.json({
      success: true,
      message: 'تم إنشاء الباركود بنجاح',
      barcode: barcode,
      svg: svgString
    });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

// Generate barcode for product
router.get('/product/:productId', async (req, res) => {
  try {
    const product = await Product.findById(req.params.productId);
    if (!product) {
      return res.status(404).json({ success: false, message: 'المنتج غير موجود' });
    }

    const barcodeValue = product.barcode || product.sku;
    const svgString = JsBarcode(barcodeValue, {
      format: 'CODE128',
      width: 2,
      height: 50,
      displayValue: true
    }).render();

    res.json({
      success: true,
      product: {
        id: product._id,
        name: product.name,
        sku: product.sku,
        barcode: barcodeValue
      },
      svg: svgString
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
});

// Print barcode labels
router.post('/print-labels', async (req, res) => {
  try {
    const { productIds, labelsPerRow = 3, labelsPerCol = 4 } = req.body;

    if (!productIds || productIds.length === 0) {
      return res.status(400).json({ success: false, message: 'اختر المنتجات' });
    }

    const products = await Product.find({ _id: { $in: productIds } });
    const doc = new PDFDocument({ size: 'A4', margin: 10 });
    const filename = `barcodes-${Date.now()}.pdf`;
    const filepath = path.join(__dirname, '../uploads', filename);

    doc.pipe(fs.createWriteStream(filepath));

    let row = 0, col = 0;
    const labelWidth = 210 / labelsPerRow; // A4 width in mm = 210mm
    const labelHeight = 297 / labelsPerCol; // A4 height in mm = 297mm

    for (const product of products) {
      const x = col * (labelWidth * 2.834); // Convert mm to points
      const y = row * (labelHeight * 2.834);

      doc.fontSize(10).text(product.name, x, y, { width: labelWidth * 2.834 - 10 });
      doc.fontSize(8).text(`SKU: ${product.sku}`, x, y + 15);
      doc.fontSize(8).text(`السعر: ${product.sellingPrice}`, x, y + 30);

      const barcode = product.barcode || product.sku;
      const svgString = JsBarcode(barcode, {
        format: 'CODE128',
        width: 1.5,
        height: 30
      }).render();

      doc.rect(x, y + 45, labelWidth * 2.834 - 10, 40).stroke();

      col++;
      if (col >= labelsPerRow) {
        col = 0;
        row++;
      }
    }

    doc.end();

    doc.on('finish', () => {
      res.json({
        success: true,
        message: 'تم إنشاء ملف الباركود بنجاح',
        downloadUrl: `/uploads/${filename}`
      });
    });
  } catch (error) {
    res.status(400).json({ success: false, message: error.message });
  }
});

module.exports = router;