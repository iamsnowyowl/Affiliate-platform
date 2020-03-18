package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.AddSchedule;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;
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
import java.util.Map;
import java.util.Objects;

import butterknife.BindView;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class AddScheduleActivity extends BaseActivity implements AddScheduleContract.View {

    @BindView(R.id.pick_schedule_date)
    MaterialCalendarView calendarView;

    SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd", Locale.US);
    HashMap<String, String> map = new HashMap<String, String>();
    List<ScheduleAccessor> scheduleAccessor = new ArrayList<>();
    AddSchedulePresenter presenter = new AddSchedulePresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_add_schedule;
    }

    @OnClick(R.id.save_schedule_date)
    public void onScheduleSave() {
        new MaterialStyledDialog.Builder(this)
                .setTitle(R.string.are_you_sure)
                .setDescription(R.string.schedule_confirmation)
                .setHeaderColor(R.color.primaryColorDark)
                .setIcon(R.drawable.calendar)
                .setCancelable(true)
                .setPositiveText(R.string.yes)
                .onPositive((dialog, which) -> {
                    for (Map.Entry<String, String> entry : map.entrySet()) {
                        String value = entry.getValue();
                        ScheduleAccessor accessor = new ScheduleAccessor();
                        accessor.setScheduleDates(value);
                        scheduleAccessor.add(accessor);
                    }
                    presenter.postScheduleDates(scheduleAccessor);
                    map.clear();
                    scheduleAccessor = new ArrayList<>();
                })
                .setNegativeText(R.string.no)
                .onNegative((dialog, which) -> {
                    dialog.dismiss();
                    map.clear();
                    scheduleAccessor = new ArrayList<>();
                })
                .show();
    }

    @OnClick(R.id.btn_close_add_certificate)
    public void closePressed() {
        finish();
    }

    @Override
    public void onResume() {
        super.onResume();
        presenter.getScheduleAccessor();
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
        finish();
    }

    @Override
    public void startActivity(Class<?> c) {
        Intent intent = new Intent(this, c);
        startActivity(intent);
        finish();
    }

    @Override
    public void initViews() {
        calendarView.setSelectionMode(MaterialCalendarView.SELECTION_MODE_MULTIPLE);
        calendarView.addDecorator(new AddScheduleActivity.DisableBeforeCurrentday(CalendarDay.today()));
        calendarView.setOnDateChangedListener((widget, date, selected) -> {
            String dates = formatter.format(date.getDate());
            if (selected) {
                map.put(dates, dates);
            } else
                map.remove(dates);

            Log.d("GET_DATE", map.toString());
        });
    }

    @Override
    public void setScheduleAccessor(List<ScheduleAccessor> scheduleAccessorList) {
        //mapping schedule dates to calendarview
        List<String> strings = new ArrayList<>();
        for (ScheduleAccessor scheduleAccessor1 : scheduleAccessorList) {
            String s = null;
            if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.KITKAT) {
                s = Objects.toString(scheduleAccessor1, null);
            }
            strings.add(s);
        }
        for (int i = 0; i < strings.size(); i++) {
            Date date = null;
            try {
                date = formatter.parse(strings.get(i));
                map.put(strings.get(i), strings.get(i));
                calendarView.setDateSelected(date, true);
            } catch (ParseException e) {
                e.printStackTrace();
            }
        }
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
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
