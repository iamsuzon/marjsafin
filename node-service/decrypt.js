const crypto = require('crypto');

// Config
const key = Buffer.from('U29tZVN1cGVyU2VjcmV0S2V5U29SZWFsbHlTZWN1cmU=', 'base64');
const iv = Buffer.from(`1234567890123456`);

module.exports = async function decryptId(encryptedId) {
    const encryptedData = Buffer.from(encryptedId, 'base64');
    const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
    let decrypted = decipher.update(encryptedData, null, 'utf8');
    decrypted += decipher.final('utf8');
    return parseInt(decrypted, 10);
}

module.exports = async function deCipherId(encryptedId) {
    const decryptedId = encryptedId.slice(4, -4);
    return decryptedId;
}