// main.js
document.addEventListener('DOMContentLoaded', function () {
    const charts = document.querySelectorAll('.chart');
    charts.forEach(chart => {
        const ctx = chart.getContext('2d');
        const data = JSON.parse(chart.dataset.data);
        const labels = JSON.parse(chart.dataset.labels);
        const type = chart.dataset.type;

        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: chart.dataset.title,
                    data: data,
                    backgroundColor: 'rgba(231, 76, 60, 0.2)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
});