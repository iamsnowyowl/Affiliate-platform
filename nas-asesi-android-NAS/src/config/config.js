const config = {};

config.environtment = "production";
config.setup = "GPPB";
// config.setup = "CKPNI";

config.URL = {};
config.URL.staging = "https://api-staging.sertimedia.com";
config.URL.demo = "https://api-lspdemo.sertimedia.com";
// config.URL.production = "https://api-lspgppb.sertimedia.com";
config.URL.production = "https://api-lspckpni.aplikasisertifikasi.com";

config.CONTACT = {}
config.CONTACT.GPPB = "62821-1711-1400"
config.CONTACT.CKPNI = "62812-8706-3369"

export default config;
