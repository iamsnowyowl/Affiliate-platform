package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.content.Intent;
import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import butterknife.ButterKnife;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.StatusKegiatanEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.LokasiUjian.LokasiUjian;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.R;

import java.text.ParseException;
import java.util.List;

import butterknife.BindView;

import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

public class HistoryAdapter extends RecyclerView.Adapter<HistoryAdapter.HistoryHolder> {

    private List<AssessmentSchedule> historyList;

    public HistoryAdapter(List<AssessmentSchedule> historyList) {
        this.historyList = historyList;
        this.notifyDataSetChanged();
    }

    @Override
    public HistoryHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_history, parent, false);

        return new HistoryAdapter.HistoryHolder(itemView);
    }

    @Override
    public void onBindViewHolder(HistoryAdapter.HistoryHolder holder, int position) {
        AssessmentSchedule historyModel = historyList.get(position);
        holder.historyAsesmen.setText(historyModel.getTitle());
        switch (historyModel.getLastStateSchedule()) {
            case StatusKegiatanEntity.ON_REVIEW_APPLICANT_DOCUMENT:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.pra_assessment);
                break;
            case StatusKegiatanEntity.ON_COMPLETED_REPORT:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.pra_assessment_selesai);
                break;
            case StatusKegiatanEntity.REAL_ASSESSMENT:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.assement);
                break;
            case StatusKegiatanEntity.PLENO_DOC_COMPLETED:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.pleno);
                break;
            case StatusKegiatanEntity.PLENO_REPORT_READY:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.pleno_selesai);

                break;
            case StatusKegiatanEntity.PRINT_CERTIFICATE:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.print_certificate);
                break;
            case StatusKegiatanEntity.COMPLETED:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.completed);
                break;
            default:
                holder.statusAssessment.setVisibility(View.VISIBLE);
                holder.statusAssessment.setText(R.string.soon);
        }

        holder.container.setOnClickListener(v -> {
            Intent intent = new Intent(v.getContext(), LokasiUjian.class);
            intent.putExtra(AssessmentEntity.ASSESSMENT_ID, historyModel.getScheduleAssessmentID());
            intent.putExtra(AssessmentEntity.LAT_ASSESSMENT, historyModel.getLatitude());
            intent.putExtra(AssessmentEntity.LONG_ASSESSMENT, historyModel.getLongitude());
            intent.putExtra(AssessmentEntity.ADDRESS_ASSESSMENT, historyModel.getAddress());
            intent.putExtra(AssessmentEntity.TITLE_ASSESSMENT, historyModel.getTitle());
            intent.putExtra(AssessmentEntity.NOTE_ASSESSMENT, historyModel.getNotes());
            intent.putExtra(AssessmentEntity.STARTDATE_ASSESSMENT, historyModel.getStartDate());
            intent.putExtra(AssessmentEntity.TUK_ASSESSMENT, historyModel.getTukId());
            v.getContext().startActivity(intent);
        });

        try {
            holder.historyTanggal.setText(MyUtils.dateFormatter("yyyy-MM-dd hh:mm:ss", historyModel.getStartDate(), "dd MMMM yyyy"));
        } catch (ParseException e) {
            e.printStackTrace();
        }
    }

    @Override
    public int getItemCount() {
        return historyList.size();
    }

    public class HistoryHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.historyAsesmen)
        TextView historyAsesmen;
        @BindView(R.id.historyTanggal)
        TextView historyTanggal;
//        @BindView(R.id.paid)
//        LinearLayout paid;
//        @BindView(R.id.unpaid)
//        LinearLayout unpaid;
        @BindView(R.id.report_container)
        RelativeLayout container;
        @BindView(R.id.status_assessment)
        TextView statusAssessment;

        public HistoryHolder(View itemView) {
            super(itemView);
            ButterKnife.bind(this, itemView);
        }
    }

    public void addNextPage(List<AssessmentSchedule> historyLists) {

        for (AssessmentSchedule histories : historyLists) {
            historyList.add(histories);
        }
        notifyDataSetChanged();
    }

}
