package com.springboot.controller;

import com.springboot.mapper.UserMapper;
import com.springboot.pojo.User;
import lombok.AllArgsConstructor;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@AllArgsConstructor
@RequestMapping("/demo")
public class LoginController {

    private final PasswordEncoder passwordEncoder;
    private final UserMapper userMapper;

    @GetMapping("/mainpage")
    public String showlanding(){
        return "Mainpage";
    }

    @GetMapping("/{page}")
    public String showPage(@PathVariable String page) {
        return page;
    }

    @PostMapping("login")
    public String loginFunction(User user, Model model) {
        String errorMessage = null;
        try {
            // 检查用户输入是否为空
            if (user.getEmail() == null || user.getPassword() == null) {
                errorMessage = "Please enter email and password";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("login");
            }

            User foundUser = userMapper.findByEmail(user.getEmail());
            if (foundUser == null) {
                errorMessage = "Email not found";
            } else {
                // 使用PasswordEncoder验证密码
                if (passwordEncoder.matches(user.getPassword(), foundUser.getPassword())) {
                    return showlanding();
                } else {
                    errorMessage = "Wrong password";
                    System.out.println(user.getPassword() + "密码错误");
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "Error";
        }
        model.addAttribute("errorMessage", errorMessage);
        return showPage("login");
    }

    @PostMapping("signup")
    public String sufnction(User user, Model model) {
        String errorMessage = null;
        try {
            // 检查用户输入是否为空
            if (user.getTitle() == null || user.getFirstname() == null || user.getLastname() == null || user.getLocation() == null ||
                    user.getEmail() == null || user.getAreacode() == null || user.getContact() == null || user.getPassword() == null || user.getTerms() == null) {
                errorMessage = "All fields are required";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("signup");
            }

            user.setPassword(passwordEncoder.encode(user.getPassword()));
            String maxCustId = userMapper.getMaxCustId();
            int userCount = maxCustId == null ? 1001 : Integer.parseInt(maxCustId) + 1;
            String userId = "C" + userCount;
            int affectedrows = userMapper.register(new User(userId, user.getTitle(), user.getFirstname(), user.getLastname(), user.getLocation(),
                    user.getEmail(), user.getAreacode(), user.getContact(), user.getPassword(), user.getTerms()));
            if (affectedrows > 0) {
                return "login";
            } else {
                errorMessage = "Registration failed, please try again";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("signup");
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "Error";
            model.addAttribute("errorMessage", errorMessage);
            return showPage("signup");
        }
    }

}
