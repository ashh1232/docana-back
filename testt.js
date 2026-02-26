const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
puppeteer.use(StealthPlugin());

async function run() {
    const browser = await puppeteer.launch({ 
        headless: false, // سيفتح نافذة متصفح حقيقية الآن
        args: ['--no-sandbox'] 
    });

    const page = await browser.newPage();
    const url = "https://ar.shein.com";

    console.log("🚀 سيفتح المتصفح الآن.. قم بحل الكابتشا يدوياً إذا ظهرت!");
    
    try {
        await page.goto(url, { waitUntil: 'networkidle2', timeout: 60000 });

        // انتظر حتى تحل الكابتشا وتظهر البيانات (JSON) في المتصفح
        // سيعطيك الكود 30 ثانية لتحل الكابتشا بيدك
        await page.waitForFunction(() => document.body.innerText.includes('"code":"0"'), { timeout: 60000 });

        const content = await page.evaluate(() => document.body.innerText);
        const jsonData = JSON.parse(content);
        
        console.log("✅ نجاح! تم تجاوز الحماية يدوياً.");
        console.log("عدد المنتجات:", jsonData.info.products.length);

    } catch (e) {
        console.log("❌ لم يتم حل الكابتشا في الوقت المطلوب.");
    }
    // لا تغلق المتصفح فوراً لترى النتيجة
}
run();
