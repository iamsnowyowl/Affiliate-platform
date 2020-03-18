package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.AddSertifikasi;

import android.os.Bundle;

import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.R;

public class SertifikasiSaved extends BaseActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_sertifikasi_saved;
    }

    @OnClick(R.id.btn_certification_saved)
    public void certificationSaved() {
        finish();
    }
}
