package com.aplikasisertifikasi.asesor.lspabi.Preference;

import android.content.Context;
import android.content.SharedPreferences;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;
import java.util.HashMap;

import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Core.LSPApplication;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;

import static com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity.ASESSOR_USERNAME_EMAIL;
import static com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity.USER_PERMISSIONS;


public class LSPUtils {

    private static final String PREF_KEY_IS_LOGIN = "isLogin";
    private static SharedPreferences sharedPreferences;

    public LSPUtils() {
        sharedPreferences = LSPApplication.getAppContext().getSharedPreferences(Config.ASESSOR_SHARED_PREFERENCES, Context.MODE_PRIVATE);
    }

    public static String getSecretKey() {
        return sharedPreferences.getString(AsessorEntity.ASESSOR_KEY, null);
    }

    public static void setSecretKey(String secret_key) {
        SharedPreferences.Editor editor = sharedPreferences.edit();

        editor.putString(AsessorEntity.ASESSOR_KEY, secret_key);
        editor.putBoolean(PREF_KEY_IS_LOGIN, true);
        editor.apply();
    }

    public static String getRoleCode() {
        return sharedPreferences.getString(AsessorEntity.USER_ROLE_CODE, null);
    }

    public static void setRoleCode(String role_code) {
        SharedPreferences.Editor editor = sharedPreferences.edit();

        editor.putString(AsessorEntity.USER_ROLE_CODE, role_code);
        editor.apply();
    }

    public boolean isLogin() {
        return sharedPreferences.getBoolean(PREF_KEY_IS_LOGIN, false);
    }

    public static void setUsernameEmail(String email) {
        SharedPreferences.Editor editor = sharedPreferences.edit();

        editor.putString(ASESSOR_USERNAME_EMAIL, email);
        editor.apply();
    }

    public static String getUsernameEmail() {
        return sharedPreferences.getString(ASESSOR_USERNAME_EMAIL, "");
    }

    public static void setUserPermissions(HashMap<String, String> map) {
        SharedPreferences.Editor editor = sharedPreferences.edit();

        Gson gson = new Gson();
        String json = gson.toJson(map);
        editor.putString(USER_PERMISSIONS, json);
        editor.apply();
    }

    public static HashMap<String, String> getPermissions() {
        Gson gson = new Gson();
        String json = sharedPreferences.getString(USER_PERMISSIONS, null);
        Type type = new TypeToken<HashMap<String, String>>() {
        }.getType();
        HashMap<String, String> map = gson.fromJson(json, type);

        return map;
    }

    public static String getString(String key) {
        return sharedPreferences.getString(key, "");
    }

    public static void setString(String key, String value) {
        SharedPreferences.Editor editor = sharedPreferences.edit();

        editor.putString(key, value);
        editor.apply();
    }

    //logout user
    public static void logout() {
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
    }
}
