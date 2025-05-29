const express = require('express');
const submitForm = require('./form-handler');
const fs = require('fs');
const path = require('path');

const app = express();
const port = 3000;

// Middleware to parse JSON bodies
app.use(express.json());

// Middleware to parse URL-encoded form data
app.use(express.urlencoded({ extended: true }));

app.post('/book-appointment', async (request, response) => {
    const {
        user_id,
        country,
        city,
        country_traveling_to,
        first_name,
        last_name,
        dob,
        nationality,
        gender,
        marital_status,
        passport_number,
        passport_issue_date,
        passport_issue_place,
        passport_expiry_date,
        visa_type,
        email,
        phone_number,
        nid_number,
        applied_position,
        updated_at,
        created_at,
        uid,
        webhook_url,
        webhook_type
    } = request.body;

    const fields = {
        user_id,
        country,
        city,
        country_traveling_to,
        first_name,
        last_name,
        dob,
        nationality,
        gender,
        marital_status,
        passport_number,
        passport_issue_date,
        passport_issue_place,
        passport_expiry_date,
        visa_type,
        email,
        phone_number,
        nid_number,
        applied_position,
        updated_at,
        created_at,
        uid,
        webhook_url,
        webhook_type
    };

    const emptyFields = await Object.entries(fields).filter(([key, value]) => {
        return value === null || value === undefined || value === '';
    });

    if (emptyFields.length > 0) {
        const missingKeys = emptyFields.map(([key]) => key).join(', ');
        const logMessage = `UID: ${fields.uid} - Missing fields: ${missingKeys}\n`;

        const logFilePath = path.join(__dirname, 'error_log.txt');
        fs.appendFile(logFilePath, logMessage, (err) => {
            if (err) {
                console.error('Error writing to log file:', err);
            }
        });

        return response.status(400).send(`Missing fields: ${missingKeys}`);
    }

    await submitForm(fields);
    
    // Send a response back to the client
    response.send('Form submitted successfully!');
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});