const express = require('express');
const puppeteer = require('puppeteer');
const crypto = require('crypto');
const axios = require('axios');
const https = require('https');

const app = express();
const PORT = 3000;

const agent = new https.Agent({
    rejectUnauthorized: false // ⚠️ disables SSL certificate validation
});

app.use(express.json()); // Parse JSON body

app.post('/ready-payments', async (req, res) => {
    console.log('Application start');

    let {user_id, appointment_booking_id, appointment_booking_link_id, link, card_data} = req.body;

    let card_holder_name = card_data.card_holder_name;
    let card_number = decrypt(card_data, card_data.card_number);
    let card_cvv = decrypt(card_data, card_data.card_cvv);
    let card_expiration_date = decrypt(card_data, card_data.card_expiration_date);
    card_expiration_date = card_expiration_date[2] + card_expiration_date[3] + card_expiration_date[0] + card_expiration_date[1];

    const browser = await puppeteer.launch({
        headless: true,
        // executablePath: '/usr/bin/google-chrome', for server
    });
    const page = await browser.newPage();

    let lastLink = null;

    await page.on('framenavigated', async frame => {
        const currentUrl = frame.url();
        lastLink = currentUrl;

        console.log(lastLink);

        if (lastLink.includes(`checkout.payfort.com/FortAPI/redirectionResponse/threeDs2RedirectURL?token=`)) {
            try {
                const response = await axios.post('https://marjsafin.test/api/set-payment-links', {
                    user_id: user_id,
                    appointment_booking_id: appointment_booking_id,
                    appointment_booking_link_id: appointment_booking_link_id,
                    link: lastLink
                }, {
                    httpsAgent: agent,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Links sent successfully:', response.data);
            } catch (error) {
                console.error('Error sending links:', error);
            }

            delay(1000);
            if (page && !page.isClosed()) {
                await page.close();
            }
            browser.close();
        }
    });

    try {
        await page.goto(link, {waitUntil: 'networkidle2', timeout: 60000});

        await page.waitForSelector('input[name="card_holder_name"]');
        await page.type('input[name="card_holder_name"]', card_holder_name);

        await page.waitForSelector('input[name="card_number"]');
        await page.type('input[name="card_number"]', card_number);

        await page.waitForSelector('input[name="expiry_date"]');
        await page.type('input[name="expiry_date"]', card_expiration_date);

        await page.waitForSelector('input[name="card_security_code"]');
        await page.type('input[name="card_security_code"]', card_cvv);

        await page.waitForSelector('button[type="submit"]');
        await page.click('button[type="submit"]');

        await delay(500);

        setTimeout(async () => {
            browser.close();
        }, 300000);

        res.json({
            initial_url: link,
            last_link: lastLink
        });
    } catch (err) {
        await browser.close();
        console.error('Error during navigation:', err);
        res.status(500).json({error: 'Failed to navigate', details: err.message});
    }
});

app.get('/link-pay', async (req, res) => {
    console.log('Application start');
    let { link_id, link } = req.body;

    const browser = await puppeteer.launch({
        headless: true,
        // executablePath: '/usr/bin/google-chrome', for server
    });
    const page = await browser.newPage();

    let lastLink = null;

    await page.on('framenavigated', async frame => {
        const currentUrl = frame.url();
        lastLink = currentUrl;

        console.log(lastLink);

        if(lastLink === link) {
            const title = await frame.evaluate(() => document.title);

            if (title === '500') {
                try {
                    const response = await axios.post('https://marjsafin.test/api/get-slip-info', {
                        status: 'failed',
                        link_id: link_id,
                        success_link: '',
                        message: 'Link Expired',
                    }, {
                        httpsAgent: agent,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    console.log('Links sent successfully:', response.data);
                } catch (error) {
                    console.error('Error sending links:', error);
                }

                delay(1000);
                if (page && !page.isClosed()) {
                    await page.close();
                }
                browser.close();
            }
        }
        else if (lastLink === `https://wafid.com/appointment/confirm/`) {
            try {
                const pageContent = await frame.content();

                let failedText = 'Payment Failed';
                if (pageContent.includes('Invalid authentication code.')) {
                    failedText = 'Invalid authentication code.';
                }

                const response = await axios.post('https://marjsafin.test/api/get-slip-info', {
                    status: 'failed',
                    link_id: link_id,
                    success_link: '',
                    message: failedText
                }, {
                    httpsAgent: agent,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Links sent successfully:', response.data);
            } catch (error) {
                console.error('Error sending links:', error);
            }

            delay(1000);
            if (page && !page.isClosed()) {
                await page.close();
            }
            browser.close();
        }
        else if (lastLink.includes(`/slip/`))
        {
            try {
                // Wait for table to appear
                await frame.waitForSelector('.mc-table tbody tr:first-child td:first-child', { timeout: 5000 });

                // Extract the first <td> value
                const tdText = await frame.$eval('.mc-table tbody tr:first-child td:first-child', el => el.textContent.trim());

                const response = await axios.post('https://marjsafin.test/api/get-slip-info', {
                    status: 'success',
                    link_id: link_id,
                    success_link: lastLink,
                    message: tdText
                }, {
                    httpsAgent: agent,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Links sent successfully:', response.data);
            } catch (error) {
                console.error('Error sending links:', error);
            }

            delay(1000);
            if (page && !page.isClosed()) {
                await page.close();
            }
            browser.close();
        }
    });

    try {
        await page.goto(link, {waitUntil: 'networkidle2', timeout: 60000});
        await delay(30000);

        setTimeout(async () => {
            browser.close();
        }, 300000);

        res.json({
            initial_url: link,
            last_link: lastLink
        });
    } catch (err) {
        await browser.close();
        console.error('Error during navigation:', err);
        res.status(500).json({error: 'Failed to navigate', details: err.message});
    }
});

function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function decrypt(card_data, card_value) {
    let key = card_data.access_key;

    if (key.startsWith('base64:')) {
        key = key.replace('base64:', '');
    }

    const BKey = Buffer.from(key, 'base64');
    const decoded = Buffer.from(card_value, 'base64').toString('utf8');
    const json = JSON.parse(decoded);

    // Extract IV and encrypted value
    const iv = Buffer.from(json.iv, 'base64');
    const encryptedData = Buffer.from(json.value, 'base64');

    // Decrypt
    const decipher = crypto.createDecipheriv('aes-256-cbc', BKey, iv);
    let decrypted = decipher.update(encryptedData);
    decrypted = Buffer.concat([decrypted, decipher.final()]);

    return decrypted.toString();
}

app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
