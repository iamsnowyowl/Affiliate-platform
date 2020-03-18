package com.aplikasisertifikasi.asesor.lspabi.Preference;

import com.google.firebase.auth.FirebaseAuth;

public class FirebaseUtils {
    private static FirebaseAuth firebaseAuth;

    public static FirebaseAuth getFirebaseAuth() {
        if (firebaseAuth == null)
            firebaseAuth = FirebaseAuth.getInstance();

        return firebaseAuth;
    }
}
