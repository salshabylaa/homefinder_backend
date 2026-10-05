const nodemailer = require('nodemailer');

nodemailer.createTestAccount((err, account) => {
    if (err) {
        console.error('Failed to create a testing account. ' + err.message);
        return process.exit(1);
    }
    console.log('CREDENTIALS:');
    console.log(`MAIL_MAILER=smtp`);
    console.log(`MAIL_HOST=${account.smtp.host}`);
    console.log(`MAIL_PORT=${account.smtp.port}`);
    console.log(`MAIL_USERNAME="${account.user}"`);
    console.log(`MAIL_PASSWORD="${account.pass}"`);
    console.log(`MAIL_ENCRYPTION=tls`);
    console.log(`MAIL_FROM_ADDRESS="${account.user}"`);
    console.log(`MAIL_FROM_NAME="Admin Homefinder"`);
    console.log('WEBMAIL: ' + account.web);
});
