package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Build;
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

import com.aplikasisertifikasi.asesor.lspabi.Entity.CompetenceEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.AddSertifikasi.AddSertifikasi;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.DetailSertifikasi.DetailSertifikasiActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.intrusoft.library.FrissonView;

public class SertifikasiAdapter extends RecyclerView.Adapter<SertifikasiAdapter.SertifikasiHolder> {

    private List<AccessorCompetence> accessorCompetenceList = new ArrayList<AccessorCompetence>();
    private Context context;

    public SertifikasiAdapter(Context context) {
        this.context = context;
        this.notifyDataSetChanged();
    }

    public void setAccessorList(List<AccessorCompetence> accessorCompetenceList) {
        this.accessorCompetenceList = accessorCompetenceList;
    }

    @Override
    public SertifikasiHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_sertifikasi, parent, false);
        return new SertifikasiHolder(view);
    }

    @Override
    public void onBindViewHolder(SertifikasiHolder holder, int position) {
        AccessorCompetence accessorCompetence = accessorCompetenceList.get(position);
        holder.schemaSertifikasi.setText(accessorCompetence.getSubSchemaName());
        holder.detailSkill.setOnClickListener(v -> {
            Intent intent = new Intent(v.getContext(), DetailSertifikasiActivity.class);
            intent.putExtra(CompetenceEntity.ACCESSOR_COMPETENCE_ID, accessorCompetence.getAccessorCompetenceId());
            v.getContext().startActivity(intent);
        });
        holder.addSkill.setVisibility(View.GONE);
        if (accessorCompetence.getVerificationFlag() == 0) {
            holder.status.setVisibility(View.VISIBLE);
        } else {
            holder.status.setVisibility(View.GONE);
        }
        if (position == accessorCompetenceList.size() - 1) {
            holder.addSkill.setVisibility(View.VISIBLE);
            holder.addSkill.setOnClickListener(v -> {
                Intent intent = new Intent(v.getContext(), AddSertifikasi.class);
                v.getContext().startActivity(intent);
            });
        }
    }

    @Override
    public int getItemCount() {
        return accessorCompetenceList.size();
    }

    public class SertifikasiHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.title_sertifikasi)
        TextView schemaSertifikasi;
        @BindView(R.id.status_sertifikasi)
        TextView status;
        @BindView(R.id.btn_add_skill)
        CardView addSkill;
        @BindView(R.id.detail_accessor_skill)
        CardView detailSkill;
        @BindView(R.id.frisson_view)
        FrissonView frissonView;

        public SertifikasiHolder(View itemView) {
            super(itemView);
            ButterKnife.bind(this, itemView);

            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.P) {
                frissonView.setVisibility(View.INVISIBLE);
                status.setTextColor(ContextCompat.getColor(status.getContext(), R.color.md_grey_50));
            }
        }
    }
}
