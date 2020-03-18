package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.Snackbar;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.TextView;

import com.prolificinteractive.materialcalendarview.CalendarDay;
import com.prolificinteractive.materialcalendarview.DayViewDecorator;
import com.prolificinteractive.materialcalendarview.DayViewFacade;
import com.prolificinteractive.materialcalendarview.MaterialCalendarView;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.Adapter.ScheduleAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.ScheduleEntity;
import com.aplikasisertifikasi.asesor.lspabi.Listener.RecyclerViewListener;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragment;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.AddSchedule.AddScheduleActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.DetailSchedule.DetailSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleModel;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class Schedule extends BaseFragment implements ScheduleContract.View, RecyclerViewListener<ScheduleModel> {

    @BindView(R.id.layout_schedule)
    FrameLayout layout;
    @BindView(R.id.schedule_date)
    MaterialCalendarView calendarView;
    @BindView(R.id.toolbar_title)
    TextView fragmentTitle;
    @BindView(R.id.btn_to_add_schedule)
    ImageView btnAddSchedule;

    SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd", Locale.US);
    HashMap<String, String> map = new HashMap<String, String>();
    List<ScheduleAccessor> scheduleAccessor = new ArrayList<>();
    SchedulePresenter presenter = new SchedulePresenter(this);

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_schedule, container, false);
        ButterKnife.bind(this, view);
        presenter.start();

        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
            fragmentTitle.setText(R.string.assessment_schedule);
            btnAddSchedule.setVisibility(View.GONE);
        } else {
            fragmentTitle.setText(R.string.your_schedule);
            btnAddSchedule.setVisibility(View.VISIBLE);
        }

        return view;
    }

    @OnClick(R.id.btn_to_add_schedule)
    public void onButtonPressed() {
        startActivity(new Intent(getContext(), AddScheduleActivity.class));
    }

    @Override
    public void onItemChooseCallback(View view, ScheduleModel scheduleModel, int position) {
        if (view.getId() == R.id.schedule_relative_layout) {
            Intent intent = new Intent(getContext(), DetailSchedule.class);
            intent.putExtra(ScheduleEntity.ID, scheduleModel.getId());
            startActivity(intent);
        }
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        presenter.end();
    }

    @Override
    public void onPause() {
        super.onPause();
        presenter.onPause();
    }

    @Override
    public void showLoadingView() {
        ProgressLoadingBar.show(getContext());
    }

    @Override
    public void dismissLoadingView() {
        ProgressLoadingBar.dismiss();
    }

    @Override
    public void errorLoadingView() {
    }

    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(getContext(), c));
    }

    @Override
    public void onResume() {
        super.onResume();
        scheduleAccessor.clear();
        calendarView.clearSelection();
        presenter.getScheduleAccessor();
    }

    @Override
    public void initViews() {
        calendarView.setSelectionMode(MaterialCalendarView.SELECTION_MODE_NONE);
        calendarView.addDecorator(new DisableBeforeCurrentday(CalendarDay.today()));
    }

    @Override
    public void setScheduleAccessor(List<ScheduleAccessor> scheduleAccessorList) {
        //mapping schedule dates to calendarview
        scheduleAccessor = scheduleAccessorList;
        for (int i = 0; i < scheduleAccessor.size(); i++) {
            Date date;
            try {
                date = formatter.parse(String.valueOf(scheduleAccessor.get(i).getScheduleDates()));
                calendarView.setDateSelected(date, true);
            } catch (ParseException e) {
                e.printStackTrace();
            }
        }
    }

    @Override
    public void showSnackBar(String message) {
        Snackbar.make(layout, message, Snackbar.LENGTH_SHORT).show();
    }

    private static class DisableBeforeCurrentday implements DayViewDecorator {
        CalendarDay dates;

        DisableBeforeCurrentday(CalendarDay dates) {
            this.dates = dates;
        }

        @Override
        public boolean shouldDecorate(CalendarDay day) {
            return day.isBefore(dates);
        }

        @Override
        public void decorate(DayViewFacade view) {
            view.setDaysDisabled(true);
        }
    }
}
