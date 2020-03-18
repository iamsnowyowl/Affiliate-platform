package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.Snackbar;
import android.support.v4.app.Fragment;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.Toast;

import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings.SettingsActivity;
import com.victor.loading.rotate.RotateLoading;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.SertifikasiAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile.EditProfile;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile.PaktaIntegritas.EditPaktaIntegritas;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.AddSertifikasi.AddSertifikasi;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Services.PermissionHelper;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

public class ProfileActivity extends Fragment implements ProfileContract.View, GoogleApiClient.OnConnectionFailedListener {

    @BindView(R.id.recyclerSertifikasi)
    RecyclerView recyclerSertifikasi;
    @BindView(R.id.imgProfile)
    ImageView imgProfile;
    @BindView(R.id.profile_portofolio)
    TextView portofolioText;
    @BindView(R.id.nama_asessor)
    TextView namaAsessor;
    @BindView(R.id.profilEmailAsessor)
    TextView emailAsessor;
    @BindView(R.id.empty_certificate)
    ImageView imgEmptyCertificate;
    @BindView(R.id.no_add_certificate)
    TextView noCertificate;
    @BindView(R.id.add_yours)
    TextView addYours;
    @BindView(R.id.btn_add_skill_in_empty)
    Button addSkillInEmpty;
    @BindView(R.id.layout)
    ScrollView linearLayout;
    @BindView(R.id.layout_empty_certificate)
    LinearLayout layoutEmptyCertificate;
    @BindView(R.id.rotate_loading)
    RotateLoading rotateLoading;
    @BindView(R.id.toolbar_profile)
    android.support.v7.widget.Toolbar toolbar;
    @BindView(R.id.error_container)
    LinearLayout errorContainer;
    @BindView(R.id.try_again_button)
    Button tryAgainButton;
    @BindView(R.id.layout_profile_management)
    LinearLayout managementProfile;
    @BindView(R.id.layout_assessor_in_profile)
    RelativeLayout assessorProfile;
    @BindView(R.id.contact_management)
    TextView contactManagement;
    @BindView(R.id.address_management)
    TextView addressManagement;
    @BindView(R.id.level_management)
    TextView levelManagement;

    private ProfilePresenter presenter;
    private GoogleApiClient googleApiClient;
    int integrityFlag;
    PermissionHelper permissionHelper;
    LinearLayoutManager linearLayoutManager;
    SertifikasiAdapter adapter = new SertifikasiAdapter(getContext());

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter = new ProfilePresenter(this, getContext());
        setHasOptionsMenu(true);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_profile, container, false);
        ButterKnife.bind(this, view);

        permissionHelper = new PermissionHelper(requireActivity());
        presenter.start();

        linearLayoutManager = new LinearLayoutManager(getContext(), LinearLayoutManager.HORIZONTAL, false);
        recyclerSertifikasi.setAdapter(adapter);
        recyclerSertifikasi.setLayoutManager(linearLayoutManager);

        ((AppCompatActivity) getActivity()).setSupportActionBar(toolbar);

        return view;
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        super.onCreateOptionsMenu(menu, inflater);
        inflater.inflate(R.menu.profile_menu, menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.btnEditProfile:
                startActivity(new Intent(getContext(), EditProfile.class));

                return true;
            case R.id.btnSettings:
                startActivity(new Intent(getContext(), SettingsActivity.class));

                return true;
            case R.id.btnLogout:
                new MaterialStyledDialog.Builder(getContext())
                        .setTitle(R.string.logout)
                        .setDescription(R.string.logout_confirmation)
                        .setHeaderColor(R.color.md_yellow_700)
                        .setIcon(R.drawable.info)
                        .setPositiveText(R.string.yes)
                        .onPositive((dialog, which) -> {
                            presenter.logout();
                            getActivity().finishAffinity();
                        })
                        .setNegativeText(R.string.no)
                        .onNegative((dialog, which) -> dialog.dismiss())
                        .show();

                return true;
        }

        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onResume() {
        super.onResume();
        presenter.getProfile();
        presenter.getAccessorSkill();
    }


    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(getView().getContext(), c));
    }

    @Override
    public void initViews() {
        namaAsessor.setText(LSPUtils.getString(AsessorEntity.USER_FULL_NAME));
        emailAsessor.setText(LSPUtils.getString(AsessorEntity.USER_EMAIL));

        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
            managementProfile.setVisibility(View.VISIBLE);
            assessorProfile.setVisibility(View.GONE);
            errorContainer.setVisibility(View.GONE);
            layoutEmptyCertificate.setVisibility(View.GONE);
        } else {
            managementProfile.setVisibility(View.GONE);
            assessorProfile.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public void logout(Class c) {
        startActivity(new Intent(getView().getContext(), c));
        getActivity().finishAffinity();
    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    @Override
    public void showSnackBar(String message) {
        Snackbar.make(linearLayout, message, Snackbar.LENGTH_SHORT).show();
    }

    @OnClick(R.id.btn_add_skill_in_empty)
    public void addSkill() {
        Intent intent = new Intent(getContext(), AddSertifikasi.class);
        startActivity(intent);
//        if (integrityFlag == 1) {
//            Intent intent = new Intent(getContext(), AddSertifikasi.class);
//            startActivity(intent);
//        } else {
//            new MaterialStyledDialog.Builder(getContext())
//                    .setTitle("Ups")
//                    .setDescription(R.string.integrity_pact_attention)
//                    .setHeaderColor(R.color.md_yellow_700)
//                    .setIcon(R.drawable.info)
//                    .setPositiveText(R.string.yes)
//                    .onPositive((dialog, which) -> {
//                        String flag = String.valueOf(integrityFlag);
//                        Intent intent = new Intent(getContext(), EditPaktaIntegritas.class);
//                        intent.putExtra("integrity_pact", flag);
//                        startActivity(intent);
//                    })
//                    .setNegativeText(R.string.no)
//                    .onNegative((dialog, which) -> dialog.dismiss())
//                    .show();
//        }
    }

    @SuppressLint("ResourceAsColor")
    @Override
    public void setContent(Profile profile) {
        namaAsessor.setText(profile.getFirstName() + " " + profile.getLastName());
        emailAsessor.setText(profile.getEmail());
        addressManagement.setText(profile.getAddress());
        contactManagement.setText(profile.getContact());

        MyUtils.getImageWithGlide(getContext(), profile.getPicture(), imgProfile);
        imgProfile.setOnClickListener(view ->
//                MyUtils.showImagePopupDialog(getContext(), profile.getPicture())
                        startActivity(new Intent(getContext(), EditProfile.class))
        );

        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
            if (profile.getLevelManagement().equals("1")) {
                levelManagement.setText(R.string.head_of_lsp);
            } else if (profile.getLevelManagement().equals("2")) {
                levelManagement.setText(R.string.co_head_of_lsp);
            }
        }

        integrityFlag = profile.getIntegrityFlag();
    }

    @Override
    public void setAccessorSkill(List<AccessorCompetence> listAccessorSkill) {
        adapter.setAccessorList(listAccessorSkill);
        if (listAccessorSkill.size() == 0) {
            errorContainer.setVisibility(View.GONE);
            portofolioText.setVisibility(View.GONE);
            layoutEmptyCertificate.setVisibility(View.VISIBLE);
            imgEmptyCertificate.setImageResource(R.drawable.empty);
            noCertificate.setText(R.string.certificate);
            addYours.setText(R.string.add_certificate);
            addSkillInEmpty.setVisibility(View.VISIBLE);
            recyclerSertifikasi.setVisibility(View.GONE);
        } else {
            portofolioText.setVisibility(View.VISIBLE);
            errorContainer.setVisibility(View.GONE);
            layoutEmptyCertificate.setVisibility(View.GONE);
            recyclerSertifikasi.setVisibility(View.VISIBLE);
        }
        adapter.notifyDataSetChanged();
    }

    @Override
    public void showLoadingView() {
        rotateLoading.start();
    }

    @Override
    public void dismissLoadingView() {
        rotateLoading.stop();
    }

    @Override
    public void errorLoadingView() {
        errorContainer.setVisibility(View.VISIBLE);
        recyclerSertifikasi.setVisibility(View.GONE);
        layoutEmptyCertificate.setVisibility(View.GONE);
        tryAgainButton.setOnClickListener(v -> {
            errorContainer.setVisibility(View.GONE);
            presenter.getProfile();
            presenter.getAccessorSkill();
        });
    }
}
