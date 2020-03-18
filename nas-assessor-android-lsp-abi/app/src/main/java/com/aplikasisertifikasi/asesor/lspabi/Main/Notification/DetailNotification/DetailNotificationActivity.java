package com.aplikasisertifikasi.asesor.lspabi.Main.Notification.DetailNotification;

import android.Manifest;
import android.content.pm.PackageManager;
import android.support.v4.app.ActivityCompat;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import java.text.ParseException;
import java.text.SimpleDateFormat;

import butterknife.BindView;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.Entity.NotificationsEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class DetailNotificationActivity extends BaseActivity implements DetailNotificationContract.View, OnMapReadyCallback {

    @BindView(R.id.btn_decline)
    Button btnDecline;
    @BindView(R.id.btn_accept)
    Button btnAccept;
    @BindView(R.id.detail_notification_execution_time)
    TextView execution_time;
    @BindView(R.id.detail_notification_title)
    TextView title;
    @BindView(R.id.detail_notification_assessment_address)
    TextView assessmentAddress;
    @BindView(R.id.detail_notification_description)
    TextView description;
    @BindView(R.id.detail_notification_note)
    TextView notes;
    String assessmentScheduleID;
    String address;
    private GoogleMap mMap;
    SupportMapFragment mapFragment;
    DetailNotificationPresenter presenter = new DetailNotificationPresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);

        presenter.start();
        assessmentScheduleID = getIntent().getStringExtra(NotificationsEntity.ASSESSMENT_ID);
        presenter.isReadNotification(getIntent().getStringExtra(NotificationsEntity.ID));
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_detail_notification;
    }

    @Override
    public void showLoadingView() {
        ProgressLoadingBar.show(this);
    }

    @Override
    public void dismissLoadingView() {
        ProgressLoadingBar.dismiss();
    }

    @Override
    public void errorLoadingView() {

    }

    @Override
    public void startActivity(Class<?> c) {

    }

    @OnClick(R.id.btn_accept)
    public void onAccept() {
        presenter.setScheduleConfirmation(assessmentScheduleID, NotificationsEntity.ACCEPT);
        btnAccept.setText("Accepted");
        btnDecline.setEnabled(false);
        btnAccept.setEnabled(false);
    }


    @OnClick(R.id.btn_decline)
    public void onDecline() {
        presenter.setScheduleConfirmation(assessmentScheduleID, NotificationsEntity.DECLINE);
        btnDecline.setText("Declined");
        btnDecline.setEnabled(false);
        btnAccept.setEnabled(false);
    }

    @Override
    public void initViews() {

    }

    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;

        presenter.getDetailNotification(getIntent().getStringExtra(NotificationsEntity.ASSESSMENT_ID));
    }

    @Override
    public void setMapLatLong(Double lat, Double lng) {
        LatLng location = new LatLng(lat, lng);

        mMap.addMarker(new MarkerOptions().position(location).title(address));
        mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(location, 15));
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            return;
        }
        mMap.setMyLocationEnabled(true);
    }

    @Override
    public void setDetailNotification(AssessmentSchedule assessmentSchedule) {
        SimpleDateFormat date = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        SimpleDateFormat dateFormat = new SimpleDateFormat("dd MMMM yyyy, HH:mm:ss");
        String startDate = null;
        String endDate = null;
        try {
            startDate = dateFormat.format(date.parse(assessmentSchedule.getStartDate()));
            endDate = dateFormat.format(date.parse(assessmentSchedule.getEndDate()));
        } catch (ParseException e) {
            e.printStackTrace();
        }

        title.setText(assessmentSchedule.getTitle());
        assessmentAddress.setText(assessmentSchedule.getAddress());
        execution_time.setText(startDate + " - " + endDate);
        description.setText(assessmentSchedule.getDescription());
        notes.setText(assessmentSchedule.getNotes());
        address = assessmentSchedule.getAddress();

        if (assessmentSchedule.getLastStateAssessor().equals(NotificationsEntity.WAIT)) {
            btnAccept.setText(R.string.accept);
            btnDecline.setText(R.string.decline);
        } else if (assessmentSchedule.getLastStateAssessor().equals(NotificationsEntity.ACCEPT)) {
            btnAccept.setText(R.string.accepted);
            btnDecline.setEnabled(false);
            btnAccept.setEnabled(false);
        } else if (assessmentSchedule.getLastStateAssessor().equals(NotificationsEntity.DECLINE)) {
            btnDecline.setText(R.string.declined);
            btnDecline.setEnabled(false);
            btnAccept.setEnabled(false);
        }

        if (Double.parseDouble(assessmentSchedule.getLatitude()) == 0.000000 && Double.parseDouble(assessmentSchedule.getLongitude()) == 0.000000)
            mapFragment.getView().setVisibility(View.GONE);
        else
            mapFragment.getView().setVisibility(View.VISIBLE);
    }
}
