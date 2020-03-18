package com.aplikasisertifikasi.asesor.lspabi.Utils;

import android.util.Base64;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Formatter;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

import com.aplikasisertifikasi.asesor.lspabi.Model.DigestAuthentication;

/**
 * Created by aridjemana on 04/10/17.
 *
 */
//@Singleton
public class DigestHelper {

    private String appName = "Lsp";
    private String username;
    private String secret;

    // @Inject
    // public DigestHelper()
    // {
    // }

    public void setUsername(String username){
        this.username = username;
    }

    public void setSecret(String secret){
        this.secret = secret;
    }

    public DigestAuthentication getDigest(String method, String path)
    {
        String date = getDate();
        String digestConstructor = method+"+"+path+"+"+date;
        String digest = "";
        try {
            digest = hash_hmac(digestConstructor, this.secret);
        } catch (Exception e) {
            e.printStackTrace();
        }
        digest = appName + " " +this.username+":"+digest;
        return new DigestAuthentication(digest, date);
    }

    private String getDate()
    {
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat("EEE, d MMM yyyy HH:mm:ss Z");
        return simpleDateFormat.format(new Date());
    }

    private String toHexString(final byte[] bytes) {
        final Formatter formatter = new Formatter();
        for (final byte b : bytes) {
            formatter.format("%02x", b);
        }
        return formatter.toString();
    }

    private String hash_hmac(String str, String secret) throws Exception{
        Mac sha512_HMAC = Mac.getInstance("HmacSHA512");

        SecretKeySpec secretKey = new SecretKeySpec(secret.getBytes(), "HmacSHA512");
        sha512_HMAC.init(secretKey);
        return Base64.encodeToString(toHexString(sha512_HMAC.doFinal(str.getBytes())).getBytes(), Base64.NO_WRAP);
    }
}
