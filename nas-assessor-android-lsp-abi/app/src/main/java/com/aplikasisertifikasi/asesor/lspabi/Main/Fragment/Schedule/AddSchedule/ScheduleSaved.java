package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.AddSchedule;

import android.os.Bundle;

import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.R;

public class ScheduleSaved extends BaseActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_schedule_saved;
    }

    @OnClick(R.id.btn_saved_ok)
    public void onPressed() {
        finish();
    }
}
