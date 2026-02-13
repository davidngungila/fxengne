/**
 * Professional Trading Color Configuration
 * Best Professional Color Setup (Recommended)
 */

export const TradingColors = {
    // Background
    background: {
        darkNavy: '#0B1C2D', // Reduces eye strain, professional look
    },

    // Candles
    candles: {
        bullish: '#00C853', // Green - Buy
        bearish: '#D50000', // Red - Sell
    },

    // Moving Averages
    movingAverages: {
        ema9: '#FFD600',    // Yellow - Fast EMA
        ema21: '#00E5FF',   // Cyan - Medium EMA
        ema200: '#FFFFFF',  // White - Trend EMA
    },

    // Support & Resistance
    supportResistance: {
        support: '#2E7D32',   // Soft Green
        resistance: '#C62828', // Soft Red
    },

    // Entry & Exit
    entryExit: {
        buyArrow: '#00C853',      // Bright Green
        sellArrow: '#D50000',     // Bright Red
        stopLoss: '#FF6D00',      // Orange
        takeProfit: '#2962FF',    // Blue
    },

    // Chart.js RGB/RGBA helpers
    toRgb: (hex) => {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    },

    toRgba: (hex, alpha = 1) => {
        const rgb = TradingColors.toRgb(hex);
        return rgb ? `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})` : hex;
    }
};

// Make available globally
if (typeof window !== 'undefined') {
    window.TradingColors = TradingColors;
}



