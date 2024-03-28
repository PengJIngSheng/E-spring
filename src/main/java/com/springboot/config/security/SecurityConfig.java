package com.springboot.config.security;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.autoconfigure.security.servlet.PathRequest;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;

@Configuration
public class SecurityConfig {

    @Autowired
    private CustomAuthenticationSuccessHandler successHandler;

    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder();
    }

    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        http
                .authorizeRequests(authorizeRequests ->
                        authorizeRequests
                                .requestMatchers(PathRequest.toStaticResources().atCommonLocations()).permitAll() // 允许访问静态资源
                                .requestMatchers("/login").permitAll() // 允许所有用户访问登录页面
                )
                .formLogin(formLogin ->
                        formLogin
                                .loginPage("/login") // 配置自定义的登录页面
                                .successHandler(successHandler) // 设置登录成功处理器
                                .permitAll()
                )
                .logout(logout -> logout
                        .logoutUrl("/logout") // 配置注销URL
                        .logoutSuccessUrl("/") // 注销成功后跳转到首页
                        .invalidateHttpSession(true) // 注销时使HTTP会话无效
                        .clearAuthentication(true) // 清除认证信息
                        .permitAll()
                );

        return http.build();
    }
}

