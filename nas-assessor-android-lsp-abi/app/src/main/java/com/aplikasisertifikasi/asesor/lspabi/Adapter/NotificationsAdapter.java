package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.google.gson.reflect.TypeToken;

import butterknife.BindView;
import butterknife.ButterKnife;
import com.aplikasisertifikasi.asesor.lspabi.Entity.NotificationsEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Notification.DetailNotification.DetailNotificationActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

public class NotificationsAdapter extends RecyclerView.Adapter<NotificationsAdapter.NotificationHolder> {
    Context context;
    private List<NotificationModel> notificationList;

    public NotificationsAdapter(Context context, List<NotificationModel> notificationList) {
        this.context = context;
        this.notificationList = notificationList;
        this.notifyDataSetChanged();
    }

    @Override
    public NotificationHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_notification, parent, false);

        return new NotificationHolder(itemView);
    }

    @SuppressLint({"ResourceAsColor", "NewApi"})
    @Override
    public void onBindViewHolder(NotificationHolder holder, int position) {
        NotificationModel notificationModel = notificationList.get(position);
        AssessmentSchedule assessmentSchedule = MyUtils.convertJSONToObject(notificationModel.getAssessmentSchedule(), new TypeToken<AssessmentSchedule>() {
        }.getType());

        DateFormat dateFormat = new SimpleDateFormat("dd MMMM yyyy, HH:mm");

        long currentTime = System.currentTimeMillis() / 1000;
        long notifTime = Long.parseLong(notificationModel.getTimeStamp());
        long diffTime = currentTime - notifTime;
        if (diffTime < 60) {
            holder.tglNotification.setText(diffTime + " " + R.string.seconds);
        } else if (diffTime > 60 && diffTime < 3600) {
            holder.tglNotification.setText(diffTime / 60 + " " + R.string.minutes);
        } else if (diffTime > 3600 && diffTime < 8600) {
            holder.tglNotification.setText(diffTime / 3600 + " " + R.string.hours);
        } else {
            holder.tglNotification.setText(dateFormat.format(new Date(notifTime * 1000)));
        }

        if (notificationModel.getIsRead().equals("0")) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                holder.layout.setBackgroundColor(context.getColor(R.color.unreadNotif));
            } else {
                holder.layout.setBackgroundColor(context.getResources().getColor(R.color.unreadNotif));
            }
        } else {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                holder.layout.setBackgroundColor(context.getColor(R.color.white));
            } else {
                holder.layout.setBackgroundColor(context.getResources().getColor(R.color.white));
            }
        }

        holder.judulNotification.setText(notificationModel.getMessage());
        holder.layout.setOnClickListener(v -> {
            Intent intent = new Intent(v.getContext(), DetailNotificationActivity.class);
            intent.putExtra(NotificationsEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
            intent.putExtra(NotificationsEntity.ID, notificationModel.getNotificationID());
            v.getContext().startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return notificationList.size();
    }

    public class NotificationHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.judul_notification)
        TextView judulNotification;
        @BindView(R.id.tgl_notification)
        TextView tglNotification;
        @BindView(R.id.layout_notification)
        RelativeLayout layout;
        @BindView(R.id.img_notif)
        ImageView roundImg;

        public NotificationHolder(View view) {
            super(view);
            ButterKnife.bind(this, view);
        }
    }

    public void addNotifications(List<NotificationModel> notifications) {

        for (NotificationModel notificationModels: notifications){
            notificationList.add(notificationModels);
        }
        notifyDataSetChanged();
    }
}
