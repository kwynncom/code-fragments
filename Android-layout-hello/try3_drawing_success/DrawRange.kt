package com.example.trydraw

import android.graphics.Bitmap
import android.graphics.Canvas
import android.graphics.Color
import android.graphics.Paint
import android.util.DisplayMetrics
import android.widget.ImageView
import android.view.WindowManager

class DrawRange (iv1 : ImageView, winm : WindowManager) {

    private val ivo = iv1
    private val windowManager = winm

    init { this.doDraw() }

    private lateinit var mImageView: ImageView
    private lateinit var bitmap: Bitmap
    private lateinit var canvas: Canvas
    private lateinit var paint: Paint

    private fun doDraw () {

        mImageView = ivo
        val displayMetrics = DisplayMetrics()
        windowManager.defaultDisplay.getMetrics(displayMetrics)

        val dw = displayMetrics.widthPixels
        val dh = displayMetrics.heightPixels

        bitmap = Bitmap.createBitmap(dw, dh, Bitmap.Config.ARGB_8888)

        canvas = Canvas(bitmap)

        paint = Paint()
        paint.color = Color.GREEN
        paint.strokeWidth = 10F

        mImageView.setImageBitmap(bitmap)

        canvas.drawLine(0F, 30F, 200F, 50F, paint)
        mImageView.invalidate()

    }

}