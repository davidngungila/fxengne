/**
 * Professional Trading Chart Configuration
 * Provides pre-configured chart options using the professional color scheme
 */

import { TradingColors } from './trading-colors';

export const ChartConfig = {
    /**
     * Default chart options with professional trading theme
     */
    defaultOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#FFFFFF',
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: TradingColors.background.darkNavy,
                titleColor: '#FFFFFF',
                bodyColor: '#FFFFFF',
                borderColor: TradingColors.movingAverages.ema21,
                borderWidth: 1,
                padding: 12
            }
        },
        scales: {
            x: {
                grid: {
                    color: TradingColors.toRgba('#FFFFFF', 0.1),
                    display: true
                },
                ticks: {
                    color: '#FFFFFF'
                }
            },
            y: {
                grid: {
                    color: TradingColors.toRgba('#FFFFFF', 0.1),
                    display: true
                },
                ticks: {
                    color: '#FFFFFF'
                }
            }
        }
    },

    /**
     * Candlestick chart dataset configuration
     */
    candlestickDataset: {
        bullish: {
            color: TradingColors.candles.bullish,
            borderColor: TradingColors.candles.bullish,
            backgroundColor: TradingColors.toRgba(TradingColors.candles.bullish, 0.3)
        },
        bearish: {
            color: TradingColors.candles.bearish,
            borderColor: TradingColors.candles.bearish,
            backgroundColor: TradingColors.toRgba(TradingColors.candles.bearish, 0.3)
        }
    },

    /**
     * Moving Average datasets
     */
    movingAverages: {
        ema9: {
            label: 'EMA 9',
            borderColor: TradingColors.movingAverages.ema9,
            backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema9, 0.1),
            borderWidth: 2,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 4
        },
        ema21: {
            label: 'EMA 21',
            borderColor: TradingColors.movingAverages.ema21,
            backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema21, 0.1),
            borderWidth: 2,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 4
        },
        ema200: {
            label: 'EMA 200',
            borderColor: TradingColors.movingAverages.ema200,
            backgroundColor: TradingColors.toRgba(TradingColors.movingAverages.ema200, 0.1),
            borderWidth: 2,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 4
        }
    },

    /**
     * Support and Resistance lines
     */
    supportResistance: {
        support: {
            label: 'Support',
            borderColor: TradingColors.supportResistance.support,
            borderWidth: 2,
            borderDash: [5, 5],
            pointRadius: 0
        },
        resistance: {
            label: 'Resistance',
            borderColor: TradingColors.supportResistance.resistance,
            borderWidth: 2,
            borderDash: [5, 5],
            pointRadius: 0
        }
    },

    /**
     * Entry/Exit markers
     */
    markers: {
        buy: {
            backgroundColor: TradingColors.entryExit.buyArrow,
            borderColor: TradingColors.entryExit.buyArrow,
            pointStyle: 'triangle',
            pointRadius: 8,
            pointHoverRadius: 10
        },
        sell: {
            backgroundColor: TradingColors.entryExit.sellArrow,
            borderColor: TradingColors.entryExit.sellArrow,
            pointStyle: 'triangle',
            rotation: 180,
            pointRadius: 8,
            pointHoverRadius: 10
        },
        stopLoss: {
            backgroundColor: TradingColors.entryExit.stopLoss,
            borderColor: TradingColors.entryExit.stopLoss,
            pointStyle: 'rect',
            pointRadius: 6,
            pointHoverRadius: 8
        },
        takeProfit: {
            backgroundColor: TradingColors.entryExit.takeProfit,
            borderColor: TradingColors.entryExit.takeProfit,
            pointStyle: 'rect',
            pointRadius: 6,
            pointHoverRadius: 8
        }
    },

    /**
     * Chart background color for dark theme
     */
    darkBackground: TradingColors.background.darkNavy
};

// Make available globally
if (typeof window !== 'undefined') {
    window.ChartConfig = ChartConfig;
}



