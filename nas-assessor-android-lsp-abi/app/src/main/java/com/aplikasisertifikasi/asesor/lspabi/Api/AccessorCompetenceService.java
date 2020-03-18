package com.aplikasisertifikasi.asesor.lspabi.Api;

import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.SubschemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SchemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import io.reactivex.Observable;
import retrofit2.http.Body;
import retrofit2.http.Header;
import retrofit2.http.Path;

public interface AccessorCompetenceService {

    interface GET {
        @retrofit2.http.GET("schemas")
        Observable<DataPayloadListResponse<SchemeCompetency>> getSchema(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date
        );

        @retrofit2.http.GET("schemas/{id}/sub_schemas?limit=100")
        Observable<DataPayloadListResponse<SubschemeCompetency>> getSubschema(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("id") String schemaId
        );

        @retrofit2.http.GET("me/accessor/competences")
        Observable<DataPayloadListResponse<AccessorCompetence>> getAccessorSkill(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date
        );

        @retrofit2.http.GET("me/accessor/competences/{accessor_competence_id}")
        Observable<SinglePayloadResponse<AccessorCompetence>> getDetailAccessorSkill(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("accessor_competence_id") String accessor_competence_id
        );
    }

    interface POST {
        @retrofit2.http.POST("me/accessor/competences")
        Observable<SinglePayloadResponse<AccessorCompetence>> postAccessorSkill(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Body AccessorCompetence accessorCompetence
        );
    }
}
