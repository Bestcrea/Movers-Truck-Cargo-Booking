package com.cscodetech.movers.ui;

import static com.cscodetech.movers.utils.SessionManager.login;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.cscodetech.movers.R;
import com.cscodetech.movers.model.Login;
import com.cscodetech.movers.model.UserLogin;
import com.cscodetech.movers.retrofit.APIClient;
import com.cscodetech.movers.retrofit.GetResult;
import com.cscodetech.movers.utils.CustPrograssbar;
import com.cscodetech.movers.utils.SessionManager;
import com.cscodetech.movers.utils.Utility;
import com.google.android.gms.tasks.TaskExecutors;
import com.google.firebase.FirebaseException;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.PhoneAuthCredential;
import com.google.firebase.auth.PhoneAuthProvider;
import com.google.gson.Gson;
import com.google.gson.JsonObject;

import org.json.JSONObject;

import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;

public class VerificationActivity extends BaseActivity implements GetResult.MyListener {

    @BindView(R.id.l1)
    public LinearLayout l1;
    @BindView(R.id.ed_otp1)
    public EditText edOtp1;
    @BindView(R.id.ed_otp2)
    public EditText edOtp2;
    @BindView(R.id.ed_otp3)
    public EditText edOtp3;
    @BindView(R.id.ed_otp4)
    public EditText edOtp4;
    @BindView(R.id.ed_otp5)
    public EditText edOtp5;
    @BindView(R.id.ed_otp6)
    public EditText edOtp6;
    @BindView(R.id.btn_verifyaccount)
    public TextView btnVerifyaccount;
    @BindView(R.id.txt_mobile)
    public TextView txtMobile;

    CustPrograssbar custPrograssbar;
    UserLogin user;
    private FirebaseAuth mAuth;
    private String verificationId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verification);
        ButterKnife.bind(this);
        sessionmanager = new SessionManager(VerificationActivity.this);
        custPrograssbar = new CustPrograssbar();

        user = sessionmanager.getUserDetails();
        mAuth = FirebaseAuth.getInstance();

        sendVerificationCode(user.getCcode() + user.getMobile());
        txtMobile.setText("Please, enter the verification code we send to your mobile "+user.getCcode() + user.getMobile());

        addOtpTextWatcher(edOtp1, edOtp2);
        addOtpTextWatcher(edOtp2, edOtp3);
        addOtpTextWatcher(edOtp3, edOtp4);
        addOtpTextWatcher(edOtp4, edOtp5);
        addOtpTextWatcher(edOtp5, edOtp6);
        addOtpTextWatcher(edOtp6, edOtp6);

    }

    private void addOtpTextWatcher(EditText current, EditText next) {
        current.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                Log.i("fdlk", "kgjd");
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (count == 1) {
                    next.requestFocus();
                } else if (count == 0 && next != current) {
                    current.requestFocus();
                }
            }

            @Override
            public void afterTextChanged(Editable s) {
                Log.i("fdlk", "kgjd");
            }
        });
    }

    private void sendVerificationCode(String number) {
        PhoneAuthProvider.getInstance().verifyPhoneNumber(
                number,
                60,
                TimeUnit.SECONDS,
                TaskExecutors.MAIN_THREAD,
                mCallBack
        );
    }

    private PhoneAuthProvider.OnVerificationStateChangedCallbacks
            mCallBack = new PhoneAuthProvider.OnVerificationStateChangedCallbacks() {
        @Override
        public void onCodeSent(String s, PhoneAuthProvider.ForceResendingToken forceResendingToken) {
            super.onCodeSent(s, forceResendingToken);
            verificationId = s;
        }

        @Override
        public void onVerificationCompleted(PhoneAuthCredential phoneAuthCredential) {
            String code = phoneAuthCredential.getSmsCode();
            if (code != null) {
                edOtp1.setText("" + code.substring(0, 1));
                edOtp2.setText("" + code.substring(1, 2));
                edOtp3.setText("" + code.substring(2, 3));
                edOtp4.setText("" + code.substring(3, 4));
                edOtp5.setText("" + code.substring(4, 5));
                edOtp6.setText("" + code.substring(5, 6));
                verifyCode(code);
            }
        }

        @Override
        public void onVerificationFailed(FirebaseException e) {
            UserLogin user1 = new UserLogin();
            user1.setId("0");
            user.setCcode("codeSelect");
            user.setName("CS");

            user.setEmail("cscodetech@gmail.com");
            user.setMobile("88888888888");
            user.setPassword("0000000");
            user.setRefercode("");
            sessionmanager.setUserDetails(user1);
            Toast.makeText(VerificationActivity.this, e.getMessage(), Toast.LENGTH_LONG).show();


        }
    };


    private void verifyCode(String code) {
        PhoneAuthCredential credential = PhoneAuthProvider.getCredential(verificationId, code);
        signInWithCredential(credential);
    }

    private void signInWithCredential(PhoneAuthCredential credential) {
        mAuth.signInWithCredential(credential)
                .addOnCompleteListener(task -> {
                    if (task.isSuccessful()) {

                        switch (Utility.getInstance().isvarification) {
                            case 0:
                                Intent intent = new Intent(this, ChanegPasswordActivity.class);
                                intent.putExtra("phone", user.getMobile());
                                startActivity(intent);
                                finish();
                                break;
                            case 1:
                                createUser();
                                break;
                            case 2:
                                break;
                            case 3:
                                finish();
                                break;
                            default:
                                break;
                        }
                    } else {
                        Toast.makeText(VerificationActivity.this, task.getException().getMessage(), Toast.LENGTH_LONG).show();
                    }
                });
    }


    @OnClick({R.id.btn_verifyaccount})

    public void onBindClick(View view) {
        if (view.getId() == R.id.btn_verifyaccount && validation()) {

                verifyCode(edOtp1.getText().toString() + "" + edOtp2.getText().toString() + "" + edOtp3.getText().toString() + "" + edOtp4.getText().toString() + "" + edOtp5.getText().toString() + "" + edOtp6.getText().toString());
        }
    }
    public boolean validation() {

        if (edOtp1.getText().toString().isEmpty()) {
            edOtp1.setError("");
            return false;
        }
        if (edOtp2.getText().toString().isEmpty()) {
            edOtp2.setError("");
            return false;
        }
        if (edOtp3.getText().toString().isEmpty()) {
            edOtp3.setError("");
            return false;
        }
        if (edOtp4.getText().toString().isEmpty()) {
            edOtp4.setError("");
            return false;
        }
        if (edOtp5.getText().toString().isEmpty()) {
            edOtp5.setError("");
            return false;
        }
        if (edOtp6.getText().toString().isEmpty()) {
            edOtp6.setError("");
            return false;
        }
        return true;
    }


    private void createUser() {

        custPrograssbar.prograssCreate(this);
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("name", user.getName());
            jsonObject.put("mobile", user.getMobile());
            jsonObject.put("ccode", user.getCcode());
            jsonObject.put("email", user.getEmail());
            jsonObject.put("password", user.getPassword());
            jsonObject.put("refercode", user.getRefercode());

        } catch (Exception e) {
            e.printStackTrace();
        }
        RequestBody bodyRequest = RequestBody.create(jsonObject.toString(),MediaType.parse("application/json"));
        Call<JsonObject> call = APIClient.getInterface().createUser(bodyRequest);
        GetResult getResult = new GetResult(this);
        getResult.setMyListener(this);
        getResult.callForLogin(call, "2");

    }

    @Override
    public void callback(JsonObject result, String callNo) {
        try {
            custPrograssbar.closePrograssBar();
            if (callNo.equalsIgnoreCase("2")) {
                Gson gson = new Gson();
                Login loginUser = gson.fromJson(result.toString(), Login.class);
                Toast.makeText(this, loginUser.getResponseMsg(), Toast.LENGTH_LONG).show();
                if (loginUser.getResult().equalsIgnoreCase("true")) {
                    sessionmanager.setBooleanData(login, true);
                    sessionmanager.setUserDetails(loginUser.getUserLogin());
                    startActivity(new Intent(this, HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
                }
            }

        } catch (Exception e) {
            Log.e("Error","-"+e.getMessage());

        }
    }
}