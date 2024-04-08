package com.springboot.controller;

import com.springboot.mapper.FunctionMapper;
import com.springboot.pojo.Cart;
import com.springboot.pojo.User;
import jakarta.servlet.http.HttpServletRequest;
import lombok.AllArgsConstructor;
import org.mybatis.spring.SqlSessionTemplate;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.ArrayList;

@Controller
@AllArgsConstructor
public class LoginController {

    private final PasswordEncoder passwordEncoder;

    @Autowired
    private final FunctionMapper functionMapper;
    @Autowired
    private SqlSessionTemplate sqlSessionTemplate;

    @GetMapping("/mainpage")
    public String showlanding() {
        return "Mainpage";
    }

    @GetMapping("{page}")
    public String showPage(@PathVariable String page) {
        return page;
    }

    @PostMapping("loginfnc")
    public String loginFunction(User user, Model model, HttpServletRequest request) {
        String errorMessage = null;
        try {
            if (user.getEmail() == null || user.getPassword() == null) {
                errorMessage = "Please enter email and password";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("Login");
            }
            User existingUser = functionMapper.findByEmail(user.getEmail());
            if (existingUser == null) {
                errorMessage = "Email not found";
                model.addAttribute("errorMessage", errorMessage);
                model.addAttribute("emailPlaceholder", "This email hasn't been registered");
                return showPage("Login");
            } else {
                if (passwordEncoder.matches(user.getPassword(), existingUser.getPassword())) {
                    Authentication auth = new UsernamePasswordAuthenticationToken(existingUser, null, new ArrayList<>());
                    SecurityContextHolder.getContext().setAuthentication(auth);
                    request.getSession().setAttribute("custid", ((User) auth.getPrincipal()).getCustid());
                    return "Mainpage";
                } else {
                    errorMessage = "Wrong password";
                    System.out.println(user.getPassword() + "密码错误");
                    model.addAttribute("passwordPlaceholder", errorMessage); // 将错误消息放进passwordPlaceholder中
                    return showPage("Login");
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "Error";
            model.addAttribute("errorMessage", errorMessage);
            return showPage("login");
        }
    }


    @PostMapping("signup")
    public String sufnction(User user, Model model) {
        String errorMessage = null;
        try {
            if (user.getTitle() == null || user.getFirstname() == null || user.getLastname() == null || user.getLocation() == null ||
                    user.getEmail() == null || user.getAreacode() == null || user.getContact() == null || user.getPassword() == null || user.getTerms() == null) {
                return showPage("signup");
            }

            User existingUser = functionMapper.findByEmail(user.getEmail());
            if (existingUser != null) {
                errorMessage = "This email has been registered";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("signup");
            }

            user.setPassword(passwordEncoder.encode(user.getPassword()));
            String maxCustId = functionMapper.getMaxCustId();
            int userCount = maxCustId == null ? 1001 : Integer.parseInt(maxCustId) + 1;
            String userId = "C" + userCount;
            int affectedrows = functionMapper.register(new User(userId, user.getTitle(), user.getFirstname(), user.getLastname(), user.getLocation(),
                    user.getEmail(), user.getAreacode(), user.getContact(), user.getPassword(), user.getTerms()));
            if (affectedrows > 0) {
                System.out.println(user.getEmail());
                return "Login";
            } else {
                errorMessage = "注册失败，请重试";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("signup");
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "错误";
            model.addAttribute("errorMessage", errorMessage);
            return showPage("signup");
        }
    }
}
