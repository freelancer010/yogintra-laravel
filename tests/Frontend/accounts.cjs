const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
async function page(name, result) {
    const values = {'#class_type':'Home Visit Yoga','#fromDate':'2026-11-01','#toDate':'2026-11-17'};
    const text = {}, handlers = {}, calls = [], tables = [];
    const $ = selector => ({
        attr: () => 'test-token',
        val(value) { if(value !== undefined) values[selector]=value; return values[selector]; },
        text(value) { text[selector]=value; return this; },
        css() { return this; },
        on(event, handler) { handlers[selector]=handler; return this; }
    });
    $.ajaxSetup = () => {};
    const context = {$,console,ajaxCallData: async (url,params) => {calls.push(params);return result;},createDataTable:(...args)=>tables.push(args)};
    vm.createContext(context);
    const script = fs.readFileSync(`resources/views/${name}.blade.php`,'utf8').match(/<script>([\s\S]*?)<\/script>/)[1];
    vm.runInContext(script,context);
    await new Promise(resolve=>setImmediate(resolve));
    return {context,values,text,handlers,calls,tables};
}
(async()=>{
    const response={success:1,totalCredit:100.99,totalDebit:150.01,netAmount:-49.02,undatedCount:1,undatedCredit:10.25,undatedDebit:0,data:[{class_type:'Home Visit Yoga',full_payment:100.99,payTotrainer:150.01,net_amount:-49.02}]};
    let result=await page('summary',response);
    assert.equal(result.text['#totalProfit'],'-49.02');
    const cols=result.tables[0][2];
    assert.equal(cols[3].render(null,'display',response.data[0]),'-49.02');
    assert.equal(cols[1].render(100.99,'sort'),100.99);
    assert.match(result.text['#financial-note'],/included in all-time/);
    result=await page('summary',{success:0,totalCredit:0,totalDebit:0,netAmount:0,data:[],undatedCount:0});
    assert.equal(result.text['#totalProfit'],'0.00');
    assert.equal(result.text['#totalCredit'],'0.00');
    result=await page('ledger',response);
    vm.runInContext('filter()',result.context);
    await new Promise(resolve=>setImmediate(resolve));
    assert.equal(result.calls.at(-1).class_type,'Home Visit Yoga');
    assert.equal(result.calls.at(-1).startDate,'2026-11-01');
    result.handlers['#class_type'].call({value:'Yoga Center'});
    assert.equal(result.calls.at(-1).endDate,'2026-11-17');
    assert.match(result.text['#financial-note'],/excluded from this date range/);
    console.log('PASS: signed net balances, paise, numeric sorting, empty totals, retained class/date filters, undated warnings');
})();
