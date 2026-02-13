/**
 * Professional Candlestick Chart Plugin for Chart.js
 * Creates OHLC candlestick visualization
 */

export class CandlestickChart {
    constructor(ctx, data, options = {}) {
        this.ctx = ctx;
        this.data = data;
        this.options = {
            bullishColor: options.bullishColor || '#00C853',
            bearishColor: options.bearishColor || '#D50000',
            wickColor: options.wickColor || '#FFFFFF',
            ...options
        };
        this.chart = null;
        this.init();
    }

    init() {
        // Create datasets for candlesticks and moving averages
        const datasets = this.createDatasets();
        
        this.chart = new Chart(this.ctx, {
            type: 'line',
            data: {
                labels: this.data.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#FFFFFF',
                            font: {
                                size: 11
                            },
                            usePointStyle: true,
                            padding: 15
                        },
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(11, 28, 45, 0.95)',
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF',
                        borderColor: '#00E5FF',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            title: (items) => {
                                return items[0].label;
                            },
                            label: (context) => {
                                const index = context.dataIndex;
                                const candle = this.data.candles[index];
                                if (candle) {
                                    return [
                                        `Open: $${candle.open.toFixed(2)}`,
                                        `High: $${candle.high.toFixed(2)}`,
                                        `Low: $${candle.low.toFixed(2)}`,
                                        `Close: $${candle.close.toFixed(2)}`,
                                        `Change: ${((candle.close - candle.open) / candle.open * 100).toFixed(2)}%`
                                    ];
                                }
                                return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                            }
                        }
                    },
                    zoom: {
                        zoom: {
                            wheel: {
                                enabled: true,
                                speed: 0.1
                            },
                            pinch: {
                                enabled: true
                            },
                            mode: 'x'
                        },
                        pan: {
                            enabled: true,
                            mode: 'x'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            display: true
                        },
                        ticks: {
                            color: '#FFFFFF',
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        position: 'right',
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            display: true
                        },
                        ticks: {
                            color: '#FFFFFF',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                }
            },
            plugins: [{
                id: 'candlestick',
                beforeDraw: (chart) => {
                    this.drawCandlesticks(chart);
                }
            }]
        });
    }

    createDatasets() {
        const datasets = [];
        
        // Add moving averages if available
        if (this.data.ema9) {
            datasets.push({
                label: 'EMA 9',
                data: this.data.ema9,
                borderColor: '#FFD600',
                backgroundColor: 'transparent',
                borderWidth: 1.5,
                pointRadius: 0,
                pointHoverRadius: 3,
                tension: 0.1,
                fill: false
            });
        }

        if (this.data.ema21) {
            datasets.push({
                label: 'EMA 21',
                data: this.data.ema21,
                borderColor: '#00E5FF',
                backgroundColor: 'transparent',
                borderWidth: 1.5,
                pointRadius: 0,
                pointHoverRadius: 3,
                tension: 0.1,
                fill: false
            });
        }

        if (this.data.ema200) {
            datasets.push({
                label: 'EMA 200',
                data: this.data.ema200,
                borderColor: '#FFFFFF',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3,
                tension: 0.1,
                fill: false
            });
        }

        // Add invisible line for tooltip positioning
        datasets.push({
            label: 'Price',
            data: this.data.candles.map(c => c.close),
            borderColor: 'transparent',
            backgroundColor: 'transparent',
            pointRadius: 0,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: 'rgba(255, 255, 255, 0.5)',
            pointHoverBorderColor: '#FFFFFF',
            pointHoverBorderWidth: 2
        });

        return datasets;
    }

    drawCandlesticks(chart) {
        const ctx = chart.ctx;
        const meta = chart.getDatasetMeta(chart.data.datasets.length - 1);
        const scale = chart.scales.y;
        const xScale = chart.scales.x;

        const candleWidth = Math.max(2, (xScale.width / chart.data.labels.length) * 0.6);

        chart.data.candles.forEach((candle, index) => {
            if (!meta.data[index]) return;

            const x = meta.data[index].x;
            const openY = scale.getPixelForValue(candle.open);
            const closeY = scale.getPixelForValue(candle.close);
            const highY = scale.getPixelForValue(candle.high);
            const lowY = scale.getPixelForValue(candle.low);

            const isBullish = candle.close >= candle.open;
            const color = isBullish ? this.options.bullishColor : this.options.bearishColor;
            const bodyTop = Math.min(openY, closeY);
            const bodyBottom = Math.max(openY, closeY);
            const bodyHeight = bodyBottom - bodyTop;

            // Draw wick
            ctx.strokeStyle = this.options.wickColor;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(x, highY);
            ctx.lineTo(x, lowY);
            ctx.stroke();

            // Draw body
            ctx.fillStyle = color;
            ctx.fillRect(x - candleWidth / 2, bodyTop, candleWidth, Math.max(1, bodyHeight));

            // Draw body border
            ctx.strokeStyle = color;
            ctx.lineWidth = 1;
            ctx.strokeRect(x - candleWidth / 2, bodyTop, candleWidth, Math.max(1, bodyHeight));
        });
    }

    updateData(newData) {
        this.data = { ...this.data, ...newData };
        if (this.chart) {
            this.chart.data.labels = this.data.labels;
            this.chart.data.datasets = this.createDatasets();
            this.chart.update('none'); // 'none' for smooth updates
        }
    }

    addCandle(candle) {
        this.data.candles.push(candle);
        this.data.labels.push(new Date().toLocaleTimeString());
        
        // Update moving averages
        if (this.data.ema9) {
            this.data.ema9.push(this.calculateEMA(this.data.candles.map(c => c.close), 9));
        }
        if (this.data.ema21) {
            this.data.ema21.push(this.calculateEMA(this.data.candles.map(c => c.close), 21));
        }
        if (this.data.ema200 && this.data.candles.length >= 200) {
            this.data.ema200.push(this.calculateEMA(this.data.candles.map(c => c.close), 200));
        }

        // Keep only last 500 candles
        if (this.data.candles.length > 500) {
            this.data.candles.shift();
            this.data.labels.shift();
            if (this.data.ema9) this.data.ema9.shift();
            if (this.data.ema21) this.data.ema21.shift();
            if (this.data.ema200) this.data.ema200.shift();
        }

        this.updateData(this.data);
    }

    calculateEMA(prices, period) {
        if (prices.length < period) return null;
        
        const multiplier = 2 / (period + 1);
        let ema = prices.slice(0, period).reduce((a, b) => a + b, 0) / period;
        
        for (let i = period; i < prices.length; i++) {
            ema = (prices[i] - ema) * multiplier + ema;
        }
        
        return ema;
    }

    destroy() {
        if (this.chart) {
            this.chart.destroy();
        }
    }
}

if (typeof window !== 'undefined') {
    window.CandlestickChart = CandlestickChart;
}




