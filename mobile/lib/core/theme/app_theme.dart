import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  static const primaryPurple = Color(0xFF7A1DD1);
  static const accentPurple = Color(0xFF9F5AFE);
  static const background = Color(0xFFF8FAFC); // Very light grey/white
  static const surface = Colors.white;
  static const textPrimary = Color(0xFF1E293B);
  static const textSecondary = Color(0xFF64748B);

  static final lightTheme = ThemeData(
    useMaterial3: true,
    brightness: Brightness.light,
    scaffoldBackgroundColor: background,
    primaryColor: primaryPurple,
    cardColor: surface,
    colorScheme: const ColorScheme.light(
      primary: primaryPurple,
      secondary: accentPurple,
      surface: surface,
      onPrimary: Colors.white,
      onSecondary: Colors.white,
      onSurface: textPrimary,
    ),
    textTheme:
        GoogleFonts.outfitTextTheme(ThemeData.light().textTheme).copyWith(
      titleLarge: GoogleFonts.outfit(
        fontWeight: FontWeight.bold,
        color: textPrimary,
      ),
      titleMedium: GoogleFonts.outfit(
        fontWeight: FontWeight.w600,
        color: textPrimary,
      ),
      bodyLarge: GoogleFonts.outfit(color: textPrimary),
      bodyMedium: GoogleFonts.outfit(color: textSecondary),
    ),
    appBarTheme: const AppBarTheme(
      backgroundColor: Colors.white,
      elevation: 0,
      centerTitle: false,
      iconTheme: IconThemeData(color: textPrimary),
      titleTextStyle: TextStyle(
        fontSize: 20,
        fontWeight: FontWeight.bold,
        color: textPrimary,
      ),
    ),
    inputDecorationTheme: InputDecorationTheme(
      filled: true,
      fillColor: Colors.white,
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(15),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(15),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(15),
        borderSide: const BorderSide(color: primaryPurple, width: 1.5),
      ),
      hintStyle: const TextStyle(color: Colors.grey, fontSize: 14),
      prefixIconColor: Colors.grey,
    ),
    elevatedButtonTheme: ElevatedButtonThemeData(
      style: ElevatedButton.styleFrom(
        backgroundColor: primaryPurple,
        foregroundColor: Colors.white,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        elevation: 2,
        textStyle:
            GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 16),
      ),
    ),
    iconTheme: const IconThemeData(color: textPrimary),
  );
}
