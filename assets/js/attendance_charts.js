function renderAttendanceTrendChart(ctx, labels, data) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Attendance Status',
                data: data,
                backgroundColor: 'rgba(15, 76, 117, 0.2)',
                borderColor: 'rgba(15, 76, 117, 1)',
                borderWidth: 1,
                fill: true,
                pointBackgroundColor: function(context) {
                    var value = context.dataset.data[context.dataIndex];
                    if (value === 1) return 'green'; // Present
                    if (value === 0) return 'red';   // Absent
                    if (value === 2) return 'yellow'; // Excused
                    return 'blue';
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            if (value === 1) return 'Present';
                            if (value === 0) return 'Absent';
                            if (value === 2) return 'Excused';
                            return '';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            var value = context.raw;
                            if (value === 1) label += 'Present';
                            else if (value === 0) label += 'Absent';
                            else if (value === 2) label += 'Excused';
                            return label;
                        }
                    }
                }
            }
        }
    });
}
