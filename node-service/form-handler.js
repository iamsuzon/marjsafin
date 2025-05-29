const axios = require('axios');
const https = require('https');
const { Builder, By, until, Key } = require('selenium-webdriver');
const chrome = require('selenium-webdriver/chrome');

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function humanLikeDelay(min = 1000, max = 3000) {
    const delay = Math.random() * (max - min) + min;
    await sleep(delay);
}

async function fillField(driver, element, text) {
    await element.clear();
    for (let char of text) {
        await element.sendKeys(char);
        await sleep(Math.random() * (200 - 50) + 50);
    }
}

async function handlePossibleCaptcha(driver) {
    try {
        await driver.wait(until.elementLocated(By.css('.g-recaptcha')), 3000);
        console.log("CAPTCHA detected - solve manually");
        await sleep(30000);
    } catch (_) {
        // No CAPTCHA found
    }
}

module.exports = async function submitForm(fields) {
    const options = new chrome.Options();
    options.addArguments('--disable-gpu');      // Recommended for headless
    options.addArguments('--window-size=1920,1080');

    let driver = await new Builder()
        .forBrowser('chrome')
        .setChromeOptions(options)
        .build();

    await driver.manage().window().setRect({ width: 1920, height: 1080, x: 5000, y: 5000 });

    try {
        console.log("1. Opening login page");
        await driver.get("https://wafid.com/login/");
        await sleep(5000);

        console.log("2. Navigating back to home page");
        try {
            const backLink = await driver.wait(until.elementLocated(By.xpath("//a[@class='back' and contains(@href, '/')]")), 10000);
            await backLink.click();
            await humanLikeDelay();
        } catch (e) {
            console.log("Back link not found, loading homepage");
            await driver.get("https://wafid.com/");
        }

        if (!(await driver.getCurrentUrl()).includes("wafid.com") || (await driver.getCurrentUrl()).includes("login")) {
            await driver.get("https://wafid.com/");
        }
        await sleep(5000);

        console.log("3. Navigating to appointment page via dropdown");
        try {
            const dropdown = await driver.wait(until.elementLocated(By.xpath("//div[contains(@class, 'ui pointing dropdown') and contains(., 'Medical Examinations')]")), 10000);
            await driver.actions({ bridge: true }).move({ origin: dropdown }).pause(1000).perform();

            const appointmentLink = await driver.wait(until.elementLocated(By.xpath("//a[@href='/book-appointment/' and contains(., 'Book an Appointment')]")), 10000);
            await appointmentLink.click();
        } catch (e) {
            console.log("Dropdown failed, going directly to appointment page");
            await driver.get("https://wafid.com/book-appointment/");
        }

        await humanLikeDelay(2000, 3000);

        console.log("\n4. First Attempt (with wrong phone number)");
        await driver.wait(until.elementLocated(By.name("first_name")), 15000);

        const formData = {
            "first_name": fields.first_name,
            "last_name": fields.last_name,
            "dob": fields.dob,
            "passport": fields.passport_number,
            "confirm_passport": fields.passport_number,
            "passport_issue_date": fields.passport_issue_date,
            "passport_issue_place": fields.passport_issue_place,
            "passport_expiry_on": fields.passport_expiry_date,
            "email": fields.email,
            "phone": "123456789",
            "national_id": fields.nid_number,
            "applied_position_other": '',
        };

        const dropdowns = [
            ["id_country", fields.country], ["id_city", fields.city], ["id_traveled_country", fields.country_traveling_to],
            ["id_nationality", fields.nationality], ["id_gender", fields.gender], ["id_marital_status", fields.marital_status],
            ["id_visa_type", fields.visa_type], ["id_applied_position", fields.applied_position],
        ];

        for (const [field, value] of Object.entries(formData)) {
            try {
                const element = await driver.findElement(By.name(field));
                await fillField(driver, element, value);
                await humanLikeDelay(300, 700);
            } catch (e) {
                console.log(`Couldn't fill ${field}: ${e}`);
            }
        }

        for (const [name, value] of dropdowns) {
            try {
                const selectElement = await driver.findElement(By.id(name));
                await selectElement.sendKeys(value);  // workaround as WebDriverJS lacks direct select-by-value
                await humanLikeDelay(200, 500);
            } catch (e) {
                console.log(`Couldn't select ${name}: ${e}`);
            }
        }

        try {
            // Wait for city options to be populated
            await driver.wait(until.elementLocated(By.css('select[name="city"] option[value="'+fields.city+'"]')), 10000);

            await driver.executeScript(`
                const select = document.querySelector('select[name="city"]');
                select.value = "${fields.city}";
                select.dispatchEvent(new Event('change', { bubbles: true }));
            `);
            await humanLikeDelay(500, 1000);
        } catch (e) {
            console.log("Couldn't select country or city: " + e);
        }

        try {
            
            // Wait for city options to be populated
            await driver.wait(until.elementLocated(By.css('select[name="marital_status"] option[value="'+fields.marital_status+'"]')), 10000);

            await driver.executeScript(`
                const select = document.querySelector('select[name="marital_status"]');
                select.value = "${fields.marital_status}";
                select.dispatchEvent(new Event('change', { bubbles: true }));
            `);
            await humanLikeDelay(500, 1000);
        } catch (e) {
            console.log("Couldn't select marital status: " + e);
        }

        try {
            
            // Wait for applied_position options to be populated
            await driver.wait(until.elementLocated(By.css('select[name="applied_position"] option[value="'+fields.applied_position+'"]')), 10000);

            await driver.executeScript(`
                const select = document.querySelector('select[name="applied_position"]');
                select.value = "${fields.applied_position}";
                select.dispatchEvent(new Event('change', { bubbles: true }));
            `);
            await humanLikeDelay(500, 1000);
        } catch (e) {
            console.log("Couldn't select marital status: " + e);
        }

        console.log("\nSubmitting first attempt...");
        try {
            const checkbox = await driver.findElement(By.name("confirm"));
            await driver.executeScript("arguments[0].click();", checkbox);

            const submitButton = await driver.findElement(By.xpath("//button[@type='submit']"));
            await submitButton.click();

            try {
                await driver.wait(until.elementLocated(By.css('.error, .text-danger')), 5000);
                console.log("First attempt failed (as expected)");
            } catch {
                console.log("First attempt possibly succeeded (unexpected)");
            }
        } catch (e) {
            console.log("First submission failed: " + e);
        }

        await humanLikeDelay(3000, 5000);

        console.log("\n5. Second Attempt (with correction)");
        const correctionFields = {
            "phone": fields.phone_number,
        };

        for (const [field, value] of Object.entries(correctionFields)) {
            try {
                const element = await driver.findElement(By.name(field));
                await element.clear();
                await fillField(driver, element, value);
                console.log(`Corrected ${field}`);
                await humanLikeDelay(1000, 2000);
            } catch (e) {
                console.log(`Couldn't correct ${field}: ${e}`);
            }
        }

        const dropdownss = [
            ["id_visa_type", fields.visa_type],
        ];

        for (const [name, value] of dropdownss) {
            try {
                const selectElement = await driver.findElement(By.id(name));
                await selectElement.sendKeys(value);  // workaround as WebDriverJS lacks direct select-by-value
                await humanLikeDelay(200, 500);
            } catch (e) {
                console.log(`Couldn't select ${name}: ${e}`);
            }
        }

        console.log("\nSubmitting second attempt...");
        try {
            const checkbox = await driver.findElement(By.name("confirm"));
            await driver.executeScript("arguments[0].click();", checkbox);

            const submitButton = await driver.findElement(By.xpath("//button[@type='submit']"));
            await submitButton.click();

            try {
                await driver.wait(async () => {
                    const currentUrl = await driver.getCurrentUrl();
                    return !currentUrl.includes("book-appointment");
                }, 15000);
                console.log("SUCCESS! Final URL: " + await driver.getCurrentUrl());

                // append the urlm into a file
                // const fs = require('fs');
                // fs.appendFileSync('urls.txt', await driver.getCurrentUrl() + fields.uid + '\n');

                const agent = new https.Agent({  
                    rejectUnauthorized: false  // WARNING: Don't use this in production!
                });
                
                await axios.post(fields.webhook_url, {
                    url: await driver.getCurrentUrl(),
                    uid: fields.uid
                }, { httpsAgent: agent })
                .then(response => {
                    console.log("Webhook submitted successfully:", response.data);
                }).catch(error => {
                    console.error("Error submitting webhook:", error);
                });
            } catch (e) {
                console.log("Submission completed but URL did not change: " + await driver.getCurrentUrl());
            }
        } catch (e) {
            console.log("Final submission failed: " + e);
        }

    } catch (err) {
        console.log("ERROR:", err);
    } finally {
        console.log("\nCleaning up...");
        await driver.quit();
    }
}