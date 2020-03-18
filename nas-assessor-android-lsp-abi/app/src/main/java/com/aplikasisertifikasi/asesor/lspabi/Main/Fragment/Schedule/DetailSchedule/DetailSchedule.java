package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.DetailSchedule;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;

import com.aplikasisertifikasi.asesor.lspabi.R;

import butterknife.BindView;
import butterknife.ButterKnife;

public class DetailSchedule extends AppCompatActivity implements DetailScheduleContract.View {

    @BindView(R.id.nama_produk)
    TextView nama_produk;
    @BindView(R.id.harga_produk)
    TextView harga_produk;
    @BindView(R.id.status_produk)
    TextView status_produk;
    @BindView(R.id.tgl_mulai)
    TextView tgl_mulai;
    @BindView(R.id.tgl_selesai)
    TextView tgl_selesai;
    @BindView(R.id.waktu_mulai)
    TextView waktu_mulai;
    @BindView(R.id.waktu_selesai)
    TextView waktu_selesai;
    @BindView(R.id.lokasi_produk)
    TextView lokasi_produk;
    @BindView(R.id.deskripsi_produk)
    TextView deskripsi_produk;

    String nama, harga;
    DetailSchedulePresenter presenter = new DetailSchedulePresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_schedule);
        ButterKnife.bind(this);
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
