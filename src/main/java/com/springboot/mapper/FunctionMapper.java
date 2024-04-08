package com.springboot.mapper;

import com.springboot.pojo.Cart;
import com.springboot.pojo.Product;
import com.springboot.pojo.User;
import org.apache.ibatis.annotations.*;

import java.util.List;

@Mapper
public interface FunctionMapper {

    @Insert("insert into `user` (custid, title, firstname, lastname, location, email, areacode, contact, password, terms) " +
            "values (#{custid},#{title},#{firstname},#{lastname},#{location},#{email},#{areacode},#{contact},#{password},#{terms})")
    int register(User user);

    @Select("select max(substring(custid, 2)) from `user`")
    String getMaxCustId();

    @Select("select * from `user` where email = #{email}")
    User findByEmail(String email);

    @Select("select * from `user` where email = #{email} and password = #{password}")
    User login(String email, String password);

    @Select("SELECT productimage FROM `product` where productid = #{id}")
    List<Product> getproductimage(String id);

    @Select("SELECT * FROM `product` WHERE productid = #{id}")
    Product getProductById(String id);

    @Insert("INSERT INTO cart(custid, productid, productname, productprice, productquantity, totalprice) " +
            "VALUES(#{custid}, #{productid}, #{productname}, #{productprice}, #{productquantity}, #{totalprice})")
    int insertCart(Cart cart);

    @Select("SELECT SUM(productquantity) FROM cart WHERE custid = #{custid} AND productid = #{productid}")
    Integer getQuantityInCart(@Param("custid") String custid, @Param("productid") String productid);

    @Update("UPDATE cart SET productquantity = productquantity + #{productquantity}, totalprice = totalprice + #{totalprice} WHERE custid = #{custid} AND productid = #{productid}")
    int updateCart(Cart cart);

    @Select("SELECT * FROM cart WHERE custid = #{custid}")
    List<Cart> getCartItems(@Param("custid") String custid);

}
