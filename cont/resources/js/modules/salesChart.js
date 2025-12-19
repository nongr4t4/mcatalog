/**
 * Графік продажів на дашборді адміна (Chart.js)
 */

export class SalesChart {
    constructor(canvasId, chartData) {
        this.canvas = document.getElementById(canvasId);
        this.chartData = chartData;
        this.chart = null;
    }

    /**
     * Ініціалізація графіку
     */
    init() {
        if (!this.canvas || typeof Chart === 'undefined') {
            console.warn('Chart.js not loaded or canvas not found');
            return;
        }

        const ctx = this.canvas.getContext('2d');
        
        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.chartData.labels,
                datasets: [{
                    label: 'Продажі (₴)',
                    data: this.chartData.values,
                    borderColor: '#00FF85',
                    backgroundColor: 'rgba(0, 255, 133, 0.12)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#00FF85',
                    pointBorderColor: '#121212',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: {
                            usePointStyle: true,
                            color: '#E0E0E0',
                            padding: 20,
                            font: { 
                                size: 12, 
                                weight: 'bold' 
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#B0B0B0' },
                        grid: { color: 'rgba(136, 136, 136, 0.25)' }
                    },
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            color: '#B0B0B0',
                            callback: function(value) {
                                return value.toLocaleString('uk-UA') + ' ₴';
                            }
                        },
                        grid: { color: 'rgba(136, 136, 136, 0.25)' }
                    }
                }
            }
        });
    }

    /**
     * Оновлення даних графіка
     */
    update(newData) {
        if (!this.chart) return;

        this.chart.data.labels = newData.labels;
        this.chart.data.datasets[0].data = newData.values;
        this.chart.update();
    }

    /**
     * Знищення інстансу
     */
    destroy() {
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }
    }
}

/**
 * Ініціалізація графіку на дашборді
 */
export function initDashboardChart(chartLabels, chartValues) {
    const salesChart = new SalesChart('salesChart', {
        labels: chartLabels,
        values: chartValues
    });

    salesChart.init();
    return salesChart;
}
