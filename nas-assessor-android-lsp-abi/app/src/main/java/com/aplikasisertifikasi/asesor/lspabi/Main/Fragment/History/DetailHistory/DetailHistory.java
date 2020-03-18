package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.History.DetailHistory;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;

import com.aplikasisertifikasi.asesor.lspabi.R;

public class DetailHistory extends AppCompatActivity implements DetailHistoryContract.View {

    DetailHistoryPresenter presenter = new DetailHistoryPresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_history);
    }

    @Override
    public void showLoadingView() {

    }

    @Override
    public void dismissLoadingView() {

    }

    @Override
    public void errorLoadingView() {

    }

    @Override
    public void startActivity(Class c) {

    }

    @Override
    public void initViews() {

    }
}
