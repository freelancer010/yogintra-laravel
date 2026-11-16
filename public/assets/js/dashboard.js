(() => {
    'use strict';
    const source = document.currentScript;
    const status = document.getElementById('dashboard-status');
    const number = new Intl.NumberFormat('en-IN');
    const money = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const numeric = value => Number.isFinite(Number(value)) ? Number(value) : 0;
    const text = (id, value) => { const element = document.getElementById(id); if (element) element.textContent = value; };
    const row = (container, label, value, color, detail, total = false) => {
        const item = document.createElement('div');
        if (total) item.className = 'summary-total';
        const term = document.createElement('dt');
        if (color) {
            const swatch = document.createElement('span');
            swatch.className = 'chart-swatch';
            swatch.style.backgroundColor = color;
            term.append(swatch);
        }
        term.append(document.createTextNode(label));
        const amount = document.createElement('dd');
        amount.textContent = value;
        if (detail) { const small = document.createElement('small'); small.textContent = detail; amount.append(small); }
        item.append(term, amount);
        container.append(item);
    };
    const chart = (id, emptyId, labels, values, colors) => {
        const canvas = document.getElementById(id);
        if (!canvas) return;
        const total = values.reduce((sum, value) => sum + value, 0);
        if (!total || values.some(value => value < 0)) {
            canvas.hidden = true;
            const empty = document.getElementById(emptyId);
            empty.hidden = false;
            if (values.some(value => value < 0)) empty.textContent = 'See signed amounts in the summary';
            return;
        }
        if (typeof Chart === 'undefined') {
            canvas.hidden = true;
            const empty = document.getElementById(emptyId);
            empty.textContent = 'Chart unavailable. See totals.';
            empty.hidden = false;
            return;
        }
        new Chart(canvas, {
            type: 'doughnut',
            data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 3, borderColor: '#fff', hoverOffset: 3 }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: { callbacks: {
                    label: context => `${context.label}: ${id === 'myChart' ? money.format(context.raw) : number.format(context.raw)}`
                } } }
            }
        });
    };
    fetch(source.dataset.countsUrl, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => {
            if (!response.ok) throw new Error('Counts request failed');
            return response.json();
        })
        .then(data => {
            if (!Object.hasOwn(data, 'lead') || data.success === 0) throw new Error('Counts unavailable');
            const metrics = { leads: 'lead', telecaller: 'telecaller', customer: 'customer', trainer: 'trainer', recruits: 'recruits', events: 'events', yoga: 'yoga' };
            Object.entries(metrics).forEach(([id, key]) => text(id, number.format(numeric(data[key]))));
            text('unreadTrainer', `${number.format(numeric(data.unreadTrainer))} unread`);
            text('unreadRecruits', `${number.format(numeric(data.unreadRecruits))} unread`);
            const community = document.getElementById('community-breakdown');
            if (community) {
                const labels = ['Leads', 'Telecalling', 'Customers', 'Rejected'];
                const values = ['lead', 'telecaller', 'customer', 'rejected'].map(key => Math.max(0, numeric(data[key])));
                const colors = ['#095253', '#c3a06b', '#81a58c', '#c87d69'];
                const total = values.reduce((sum, value) => sum + value, 0);
                community.replaceChildren();
                labels.forEach((label, index) => row(community, label, number.format(values[index]), colors[index], `${total ? (values[index] / total * 100).toFixed(1) : '0'}% of shown records`));
                row(community, 'Total shown', number.format(total), null, null, true);
                chart('allChart', 'community-empty', labels, values, colors);
            }
            const finance = document.getElementById('finance-breakdown');
            if (finance) {
                const credit = numeric(data.totalCredit);
                const debit = numeric(data.totalDebit);
                finance.replaceChildren();
                row(finance, 'Recorded credits', money.format(credit), '#095253');
                row(finance, 'Recorded debits', money.format(debit), '#c3a06b');
                row(finance, 'Net balance', money.format(credit - debit), null, null, true);
                chart('myChart', 'finance-empty', ['Recorded credits', 'Recorded debits'], [credit, debit], ['#095253', '#c3a06b']);
            }
            status.textContent = 'All-time overview loaded. Open a section for record details.';
        })
        .catch(() => {
            status.classList.add('is-error');
            status.textContent = 'Overview could not be loaded. Refresh to retry, or sign in again if your session expired.';
            document.querySelectorAll('[data-dashboard-count]').forEach(element => { element.textContent = '\u2014'; });
        });
})();
