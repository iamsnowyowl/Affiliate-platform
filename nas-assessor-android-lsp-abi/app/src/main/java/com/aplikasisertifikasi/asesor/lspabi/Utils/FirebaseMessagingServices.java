package com.aplikasisertifikasi.asesor.lspabi.Utils;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.support.v4.app.NotificationCompat;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import com.aplikasisertifikasi.asesor.lspabi.Entity.FirebaseEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.NotificationsEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Notification.Notifications;
import com.aplikasisertifikasi.asesor.lspabi.R;

public class FirebaseMessagingServices extends FirebaseMessagingService {

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);

//        Intent intent;
//        switch (remoteMessage.getNotification().getClickAction()) {
//            case FirebaseEntity.OFFERRING:
//                intent = new Intent(FirebaseEntity.OFFERRING);
//                intent.putExtra(NotificationsEntity.ID, remoteMessage.getData().get("notification_id"));
//                intent.putExtra(NotificationsEntity.ASSESSMENT_ID, remoteMessage.getData().get("schedule_assessment_id"));
//                intent.putExtra(NotificationsEntity.TIMESTAMP, String.valueOf(remoteMessage.getData().get("time_stamp")));
//                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
//
//                break;
//            case FirebaseEntity.DEFAULT:
//                intent = new Intent(FirebaseEntity.DEFAULT);
//                intent.putExtra(NotificationsEntity.ASSESSMENT_ID, remoteMessage.getData().get("schedule_assessment_id"));
//                intent.putExtra(NotificationsEntity.TIMESTAMP, String.valueOf(remoteMessage.getData().get("time_stamp")));
//                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
//
//                break;
//            default:
//                intent = new Intent(this, Notifications.class);
//                intent.putExtra(NotificationsEntity.ASSESSMENT_ID, remoteMessage.getData().get("schedule_assessment_id"));
//                intent.putExtra(NotificationsEntity.TIMESTAMP, String.valueOf(remoteMessage.getData().get("time_stamp")));
//                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//
//                break;
//        }
//
//        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent, PendingIntent.FLAG_ONE_SHOT);
//        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
//        NotificationCompat.Builder nBuilder = new NotificationCompat.Builder(this)
//                .setSmallIcon(R.drawable.lsp_energi)
//                .setContentTitle(remoteMessage.getNotification().getTitle())
//                .setContentText(remoteMessage.getNotification().getBody())
//                .setAutoCancel(true)
//                .setSound(defaultSoundUri)
//                .setContentIntent(pendingIntent);
//
//        NotificationManager notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
//        notificationManager.notify(0, nBuilder.build());
//
    }
}