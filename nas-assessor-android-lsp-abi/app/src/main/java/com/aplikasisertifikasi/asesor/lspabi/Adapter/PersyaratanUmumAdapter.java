package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.DownloadManager;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.os.Environment;
import android.support.v7.widget.RecyclerView;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.aplikasisertifikasi.asesor.lspabi.BuildConfig;
import com.aplikasisertifikasi.asesor.lspabi.Entity.ResourceEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat.DetailSuratMenyurat.DetailSuratMenyuratActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.Portofolio;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DexterUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;
import com.karumi.dexter.MultiplePermissionsReport;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;

import java.io.File;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

import static android.content.Context.DOWNLOAD_SERVICE;

public class PersyaratanUmumAdapter extends RecyclerView.Adapter<PersyaratanUmumAdapter.PersyaratanUmumHolder> {
    Context context;
    List<Portofolio> persyaratanUmum;
    long downloadID;
    Activity activity;

    public PersyaratanUmumAdapter(Context context, Activity activity, List<Portofolio> portofolios, long downloadID) {
        this.context = context;
        this.persyaratanUmum = portofolios;
        this.downloadID = downloadID;
        this.activity = activity;
    }

    @Override
    public PersyaratanUmumHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_portofolio, parent, false);
        return new PersyaratanUmumAdapter.PersyaratanUmumHolder(itemView);
    }

    @Override
    public void onBindViewHolder(PersyaratanUmumHolder holder, int position) {
        final Portofolio portofolio = persyaratanUmum.get(position);

        holder.portofolioName.setText(portofolio.getFormName());
        for (int i = 0; i < portofolio.getPersyaratans().size(); i++) {
            if (portofolio.getPersyaratans().size() > 1)
                holder.expandIcon.setVisibility(View.VISIBLE);

            if (portofolio.getFormType().equals("file") && portofolio.getPersyaratans().size() > 1 && !portofolio.getPersyaratans().get(i).getFormValue().equals("")) {
                holder.portofolioCheckbox.setVisibility(View.GONE);
                if (portofolio.getPersyaratans().size() < holder.childLayout.getChildCount()) {
                    for (int k = portofolio.getPersyaratans().size(); k < holder.childLayout.getChildCount(); k++) {
                        TextView fileName = (TextView) holder.childLayout.getChildAt(k);
                        holder.childLayout.setVisibility(View.GONE);
                        holder.expandIcon.setVisibility(View.GONE);
                        fileName.setVisibility(View.GONE);
                    }
                }
                for (int j = 0; j < portofolio.getPersyaratans().size(); j++) {
                    TextView currentTextView = (TextView) holder.childLayout.getChildAt(j);
                    currentTextView.setText(portofolio.getPersyaratans().get(j).getFileName());
                    int finalJ = j;
                    int finalJ1 = j;
                    currentTextView.setOnClickListener(view -> {
                        if (portofolio.getPersyaratans().get(finalJ1).getFileExtension().equals("png") ||
                                portofolio.getPersyaratans().get(finalJ1).getFileExtension().equals("jpeg") ||
                                portofolio.getPersyaratans().get(finalJ1).getFileExtension().equals("jpg")) {
                            MyUtils.showImagePopupDialog(context, portofolio.getPersyaratans().get(finalJ).getFormValue());
                        } else {
                            Intent intent = new Intent(context, DetailSuratMenyuratActivity.class);
                            intent.putExtra(ResourceEntity.WEB_SOURCE, "https://docs.google.com/gview?embedded=true&url=" + BuildConfig.BASE_URL + portofolio.getPersyaratans().get(finalJ).getFormValue());
                            view.getContext().startActivity(intent);
                        }
                    });
                }
            } else if (portofolio.getFormType().equals("file") && !portofolio.getPersyaratans().get(i).getFormValue().equals("") && portofolio.getPersyaratans().get(i).getFileExtension().equals("pdf")) {
                holder.portofolioView.setVisibility(View.VISIBLE);
                holder.portofolioCheckbox.setVisibility(View.GONE);
                holder.childLayout.setVisibility(View.GONE);
                holder.expandIcon.setVisibility(View.GONE);

                int finalI = i;
                holder.portofolioView.setOnClickListener(view -> {
                    Intent intent = new Intent(context, DetailSuratMenyuratActivity.class);
                    intent.putExtra(ResourceEntity.WEB_SOURCE, "https://docs.google.com/gview?embedded=true&url=" + BuildConfig.BASE_URL + portofolio.getPersyaratans().get(finalI).getFormValue());
                    view.getContext().startActivity(intent);
                });
            } else if (portofolio.getFormType().equals("checkbox") && portofolio.getPersyaratans().get(i).getFormValue().equals("1")) {
                holder.portofolioCheckbox.setChecked(true);
                holder.portofolioCheckbox.setVisibility(View.VISIBLE);
                holder.childLayout.setVisibility(View.GONE);
                holder.expandIcon.setVisibility(View.GONE);
            } else if (portofolio.getFormType().equals("checkbox")) {
                holder.portofolioCheckbox.setChecked(false);
                holder.portofolioCheckbox.setVisibility(View.VISIBLE);
                holder.expandIcon.setVisibility(View.GONE);
                holder.childLayout.setVisibility(View.GONE);
                holder.portofolioView.setVisibility(View.GONE);
            } else if (portofolio.getFormType().equals("file") && portofolio.getPersyaratans().get(i).getFormValue() != null && portofolio.getPersyaratans().get(i).getFileExtension().equals("png") ||
                    portofolio.getPersyaratans().get(i).getFileExtension().equals("jpg") || portofolio.getPersyaratans().get(i).getFileExtension().equals("jpeg")) {
                holder.childLayout.setVisibility(View.GONE);
                holder.expandIcon.setVisibility(View.GONE);
                holder.portofolioView.setVisibility(View.VISIBLE);
                int finalI1 = i;
                holder.portofolioView.setOnClickListener(view -> {
                    MyUtils.showImagePopupDialog(context, portofolio.getPersyaratans().get(finalI1).getFormValue());
                });
                holder.portofolioCheckbox.setVisibility(View.GONE);
            } else if (portofolio.getFormType().equals("file") && !portofolio.getPersyaratans().get(i).getFormValue().equals("")) {
                holder.childLayout.setVisibility(View.GONE);
                holder.expandIcon.setVisibility(View.GONE);
                holder.portofolioView.setVisibility(View.VISIBLE);
                holder.portofolioView.setText("Download");
                int finalI1 = i;
                holder.portofolioView.setOnClickListener(view -> {
                    DexterUtils.setPermissions(activity, new MultiplePermissionsListener() {
                        @Override
                        public void onPermissionsChecked(MultiplePermissionsReport report) {
                            DownloadFile(BuildConfig.BASE_URL + portofolio.getPersyaratans().get(finalI1).getFormValue(), portofolio.getPersyaratans().get(finalI1).getFileName());
                        }

                        @Override
                        public void onPermissionRationaleShouldBeShown(List<PermissionRequest> permissions, PermissionToken token) {
                            token.continuePermissionRequest();
                        }
                    }, android.Manifest.permission.READ_EXTERNAL_STORAGE, Manifest.permission.WRITE_EXTERNAL_STORAGE);
                });
                holder.portofolioCheckbox.setVisibility(View.GONE);
            } else {
                holder.expandIcon.setVisibility(View.GONE);
                holder.portofolioCheckbox.setVisibility(View.GONE);
                holder.childLayout.setVisibility(View.GONE);
                holder.portofolioView.setVisibility(View.GONE);
            }
        }

    }

    private void DownloadFile(String url, String filename) {

//        File file = new File(context.getExternalFilesDir(null), filename);
        File file = new File(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOWNLOADS) + "/SertimediaPortofolio", filename);
        DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));

        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.N) {
            request.setTitle(filename); // Title of the Download Notification
            request.setDescription("Downloading");// Description of the Download Notification
            request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE);// Visibility of the download Notification
            request.setDestinationUri(Uri.fromFile(file));// Uri of the destination file
            request.setRequiresCharging(false);// Set if charging is required to begin the download
            request.setAllowedOverMetered(true);// Set if download is allowed on Mobile network
            request.setAllowedOverRoaming(true);// Set if download is allowed on roaming network
        } else {
            request.setTitle(filename);// Title of the Download Notification
            request.setDescription("Downloading");// Description of the Download Notification
            request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE);// Visibility of the download Notification
            request.setDestinationUri(Uri.fromFile(file));// Uri of the destination file
            request.setAllowedOverMetered(true);// Set if download is allowed on Mobile network
            request.setAllowedOverRoaming(true);// Set if download is allowed on roaming network
        }

        DownloadManager downloadManager = (DownloadManager) context.getSystemService(DOWNLOAD_SERVICE);
        downloadID = downloadManager.enqueue(request);// enqueue puts the download request in the queue.
    }

    @Override
    public int getItemCount() {
        return persyaratanUmum.size();
    }

    public class PersyaratanUmumHolder extends RecyclerView.ViewHolder {
        @BindView(R.id.portofolio_name)
        TextView portofolioName;
        @BindView(R.id.portofolio_checkbox)
        CheckBox portofolioCheckbox;
        @BindView(R.id.portofolio_view)
        TextView portofolioView;
        @BindView(R.id.portofolio_child_layout)
        LinearLayout childLayout;
        @BindView(R.id.portofolio_expand_icon)
        ImageView expandIcon;
        int maxNoChild = 0;

        @SuppressLint("ResourceAsColor")
        public PersyaratanUmumHolder(View itemView) {
            super(itemView);
            ButterKnife.bind(this, itemView);

            for (int index = 0; index < persyaratanUmum.size(); index++) {
                int indexChild = persyaratanUmum.get(index).getPersyaratans().size();
                if (indexChild > maxNoChild) maxNoChild = indexChild;
            }
            for (int indexFileName = 0; indexFileName < maxNoChild; indexFileName++) {
                TextView fileName = new TextView(itemView.getContext());
                fileName.setId(indexFileName);
                fileName.setHeight(MyUtils.dpToPx(itemView.getContext(), 35));
                fileName.setGravity(Gravity.CENTER_VERTICAL);
                fileName.setPadding(50, 0, 0, 0);
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                    fileName.setBackgroundColor(itemView.getContext().getColor(R.color.md_grey_100));
                    fileName.setTextColor(itemView.getContext().getColor(R.color.secondaryTextColor));
                }
                LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                layoutParams.setMargins(0, 10, 0, 10);

                childLayout.addView(fileName, layoutParams);
                childLayout.setVisibility(View.GONE);
            }

            expandIcon.setOnClickListener(view -> {
                if (childLayout.getVisibility() == View.VISIBLE) {
                    expandIcon.setImageResource(R.drawable.arrow_down);
                    childLayout.setVisibility(View.GONE);
                } else {
                    expandIcon.setImageResource(R.drawable.arrow_up);
                    childLayout.setVisibility(View.VISIBLE);
                }
            });
        }
    }
}
