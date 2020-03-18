package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.aplikasisertifikasi.asesor.lspabi.Listener.RecyclerViewListener;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleModel;
import com.aplikasisertifikasi.asesor.lspabi.R;

import java.util.List;

public class ScheduleAdapter extends RecyclerView.Adapter<ScheduleAdapter.ScheduleHolder> {

    private List<ScheduleModel> scheduleList;
    private RecyclerViewListener recyclerViewListener;

    public ScheduleAdapter(List<ScheduleModel> scheduleList) {
        this.scheduleList = scheduleList;
        this.notifyDataSetChanged();
    }

    @Override

    public ScheduleHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_list_schedule, parent, false);

        return new ScheduleAdapter.ScheduleHolder(itemView);
    }

    public class ScheduleHolder extends RecyclerView.ViewHolder {
        TextView scheduleJudul, scheduleTanggal;
        RelativeLayout scheduleRelative;

        public ScheduleHolder(View itemView) {
            super(itemView);
            scheduleJudul = (TextView) itemView.findViewById(R.id.scheduleAsesmen);
            scheduleTanggal = (TextView) itemView.findViewById(R.id.scheduleDates);
            scheduleRelative = (RelativeLayout) itemView.findViewById(R.id.schedule_relative_layout);
        }
    }

    @Override
    public void onBindViewHolder(final ScheduleAdapter.ScheduleHolder holder, int position) {
        final ScheduleModel scheduleModel = scheduleList.get(position);
        holder.scheduleJudul.setText(scheduleModel.getProduk());
        holder.scheduleTanggal.setText(scheduleModel.getWaktu_mulai());
    }

    public void setRecyclerViewListener(RecyclerViewListener recyclerViewListener) {
        this.recyclerViewListener = recyclerViewListener;
    }

    @Override
    public int getItemCount() {
        return scheduleList.size();
    }

}
