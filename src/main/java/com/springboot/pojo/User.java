package com.springboot.pojo;

import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.stereotype.Component;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Component
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.AUTO)
    private String custid;
    private String title;
    private String firstname;
    private String lastname;
    private String location;
    private String email;
    private String areacode;
    private Integer contact;
    private String password;
    private Boolean terms;

}
