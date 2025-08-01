package com.cscodetech.movers.ui;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.cscodetech.movers.BuildConfig;
import com.cscodetech.movers.R;
import com.cscodetech.movers.model.ReferRespons;
import com.cscodetech.movers.model.UserLogin;
import com.cscodetech.movers.retrofit.APIClient;
import com.cscodetech.movers.retrofit.GetResult;
import com.cscodetech.movers.utils.CustPrograssbar;
import com.cscodetech.movers.utils.SessionManager;
import com.google.gson.Gson;
import com.google.gson.JsonObject;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Objects;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;

public class ReferActivity extends BaseActivity implements GetResult.MyListener {

    @BindView(R.id.img_back)
    public ImageView imgBack;

    @BindView(R.id.txt_referfrind)
    public TextView txtReferfrind;

    @BindView(R.id.txt_cridit)
    public TextView txtCridit;

    @BindView(R.id.btn_share)
    public TextView btnShare;
    CustPrograssbar custPrograssbar;

    UserLogin user;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_refer);
        ButterKnife.bind(this);

        custPrograssbar = new CustPrograssbar();
        user = sessionmanager.getUserDetails();
        getData();
    }

    private void getData() {

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("uid", user.getId());
            RequestBody bodyRequest = RequestBody.create(jsonObject.toString(),MediaType.parse("application/json"));
            Call<JsonObject> call = APIClient.getInterface().referdata(bodyRequest);
            GetResult getResult = new GetResult(this);
            getResult.setMyListener(this);
            getResult.callForLogin(call, "1");
            custPrograssbar.prograssCreate(ReferActivity.this);
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    ReferRespons respons;

    @Override
    public void callback(JsonObject result, String callNo) {
        try {
            custPrograssbar.closePrograssBar();
            if (callNo.equalsIgnoreCase("1")) {
                Gson gson = new Gson();
                respons = gson.fromJson(result.toString(), ReferRespons.class);
                if (respons.getResult().equalsIgnoreCase("true")) {
                    txtReferfrind.setText("Refer a friends, get " + sessionmanager.getStringData(SessionManager.currency) + respons.getRefercredit());
                    txtCridit.setText("Get " + sessionmanager.getStringData(SessionManager.currency) + respons.getSignupcredit() + " in credits when someone sign up using your referal link");
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

    }


    @OnClick({R.id.img_back, R.id.btn_share})
    public void onBindClick(View view) {
        int id = view.getId();
        if (id == R.id.img_back) {
            finish();
        } else if (id == R.id.btn_share) {
            try {
                Intent shareIntent = new Intent(Intent.ACTION_SEND);
                shareIntent.setType("text/plain");
                shareIntent.putExtra(Intent.EXTRA_SUBJECT, getResources().getString(R.string.app_name));
                String shareMessage = "Hey! Now use our app to share with your family or friends. User will get wallet amount on your 1st successful trip. Enter my referral code *" + respons.getCode() + "* & Enjoy your trip !!!";
                shareMessage = shareMessage + "https://play.google.com/store/apps/details?id=" + BuildConfig.APPLICATION_ID + "\n\n";
                shareIntent.putExtra(Intent.EXTRA_TEXT, shareMessage);
                startActivity(Intent.createChooser(shareIntent, "choose one"));
            } catch (Exception e) {
                Log.e("error", Objects.requireNonNull(e.getMessage()));
            }
        }
    }


}