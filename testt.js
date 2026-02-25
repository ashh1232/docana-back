const axios = require('axios');

async function getSheinProducts() {
    const url = "https://ar.shein.com";
    
    try {
        const response = await axios.get(url, {
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept': 'application/json, text/plain, */*',
                'Referer': 'https://ar.shein.com',
                // انسخ الـ Cookie من المتصفح وضعها هنا إذا استمر الـ 403
                'Cookie': '' 
            }
        });

        const products = response.data.info.products || [];
        console.log(`تم جلب ${products.length} منتج بنجاح!`);
        
        products.forEach(p => {
            console.log(`- ${p.goods_name} | السعر: ${p.salePrice.amountWithSymbol}`);
        });

    } catch (error) {
        console.error("حدث خطأ:", error.response ? error.response.status : error.message);
    }
}

getSheinProducts();
