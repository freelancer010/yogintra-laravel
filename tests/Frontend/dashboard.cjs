const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const source = fs.readFileSync('public/assets/js/dashboard.js', 'utf8');
class Element {
    constructor() { this.children = []; this.style = {}; this.classList = { add: name => { this.className = name; } }; this.hidden = true; this.textContent = ''; }
    append(...items) { this.children.push(...items); }
    replaceChildren() { this.children = []; }
}
async function run(data, {fail = false, charts = true, chartLibrary = true} = {}) {
    const ids = ['dashboard-status','leads','trainer','customer','telecaller','recruits','events','yoga','unreadTrainer','unreadRecruits'];
    if (charts) ids.push('community-breakdown','finance-breakdown','allChart','myChart','community-empty','finance-empty');
    const elements = Object.fromEntries(ids.map(id => [id, new Element()]));
    const chartCalls = [];
    const context = { Intl, document: {currentScript: {dataset: {countsUrl: '/counts'}}, getElementById: id => elements[id], createElement: () => new Element(), createTextNode: text => text, querySelectorAll: () => [elements.leads]}, fetch: async () => ({ok: !fail, json: async () => data}) };
    if (chartLibrary) context.Chart = function (canvas, options) { chartCalls.push(options); };
    vm.runInNewContext(source, context);
    await new Promise(resolve => setImmediate(resolve));
    return {elements, chartCalls};
}
(async () => {
    const base = {lead:128,customer:86,telecaller:42,rejected:14,trainer:24,recruits:12,events:8,yoga:6,totalCredit:425000,totalDebit:178000};
    let result = await run(base);
    assert.equal(result.chartCalls.length, 2);
    assert.equal(result.chartCalls[1].data.datasets[0].data.length, 2);
    assert.equal(result.chartCalls[0].options.maintainAspectRatio, false);
    assert.equal(result.elements['finance-breakdown'].children[2].children[1].textContent, '\u20b92,47,000');
    result = await run({...base,totalDebit:500000});
    assert.equal(result.elements['finance-breakdown'].children[2].children[1].textContent, '-\u20b975,000');
    assert.ok(result.chartCalls[1].data.datasets[0].data.every(amount => amount >= 0));
    result = await run({lead:0,totalCredit:0,totalDebit:0});
    assert.equal(result.chartCalls.length, 0);
    assert.equal(result.elements['community-empty'].hidden, false);
    result = await run({...base,totalCredit:-10});
    assert.equal(result.chartCalls.length, 1);
    assert.match(result.elements['finance-empty'].textContent, /signed amounts/);
    result = await run(base,{fail:true});
    assert.equal(result.elements['dashboard-status'].className, 'is-error');
    result = await run(base,{charts:false});
    assert.equal(result.chartCalls.length, 0);
    assert.equal(result.elements.leads.textContent, '128');
    result = await run(base,{chartLibrary:false});
    assert.equal(result.elements['finance-breakdown'].children.length, 3);
    assert.match(result.elements['finance-empty'].textContent, /unavailable/);
    console.log('PASS: normal data, negative balance, zero totals, signed inputs, request failure, roles without charts, unavailable chart library');
})();
