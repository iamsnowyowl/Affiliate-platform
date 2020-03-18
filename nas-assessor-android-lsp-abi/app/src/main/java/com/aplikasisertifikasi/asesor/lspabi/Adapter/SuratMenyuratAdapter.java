package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

import com.aplikasisertifikasi.asesor.lspabi.BuildConfig;
import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.ResourceEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat.DetailSuratMenyurat.DetailSuratMenyuratActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.R;

public class SuratMenyuratAdapter extends RecyclerView.Adapter<SuratMenyuratAdapter.SuratMenyuratHolder> {

    Context context;
    List<AssessmentLetters> assessmentLetters;

    public SuratMenyuratAdapter(Context context, List<AssessmentLetters> assessmentLetters) {
        this.context = context;
        this.assessmentLetters = assessmentLetters;
    }

    @Override
    public SuratMenyuratHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_surat_menyurat, parent, false);
        return new SuratMenyuratHolder(view);
    }

    @Override
    public void onBindViewHolder(SuratMenyuratHolder holder, int position) {
        AssessmentLetters assessmentLetter = assessmentLetters.get(position);
        String url = assessmentLetter.getUrl().replace("edit", "preview");

        if (assessmentLetter.getSignatureFlag().equals("1")) {
            holder.statusSurat.setVisibility(View.VISIBLE);
            holder.statusSurat.setText(R.string.letter_was_agreed);
        } else if (assessmentLetter.getSignatureFlag().equals("0")) {
            holder.descSurat.setVisibility(View.VISIBLE);
            holder.statusSurat.setVisibility(View.VISIBLE);
            holder.statusSurat.setText(R.string.letter_was_disagreed);
            holder.descSurat.setText("Ket : " + assessmentLetter.getDescription());
        }

        holder.namaSurat.setText(assessmentLetter.getNamaSurat());
        holder.container.setOnClickListener(view -> {
            Intent intent = new Intent(view.getContext(), DetailSuratMenyuratActivity.class);
            intent.putExtra(ResourceEntity.WEB_SOURCE, url);
            intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentLetter.getAssessmentId());
            intent.putExtra(AssessmentEntity.LETTER_ID, assessmentLetter.getLetterId());
            intent.putExtra(AssessmentEntity.LETTER_SINGNATURE_FLAG, assessmentLetter.getSignatureFlag());
            view.getContext().startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return assessmentLetters.size();
    }

    public class SuratMenyuratHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.row_surat_menyurat_nama_surat)
        TextView namaSurat;
        @BindView(R.id.row_surat_menyurat_status)
        TextView statusSurat;
        @BindView(R.id.row_surat_menyurat_desc)
        TextView descSurat;
        @BindView(R.id.row_surat_menyurat_container)
        RelativeLayout container;

        public SuratMenyuratHolder(View itemView) {
            super(itemView);
            ButterKnife.bind(this, itemView);
        }
    }
}
